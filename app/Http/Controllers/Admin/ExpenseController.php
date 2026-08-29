<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Expense::with('user');
        $user = \Auth::user();

        // Limit cleaner to only see their own salary expenses
        if ($user->role && $user->role->name === 'Cleaner') {
            $query->where('user_id', $user->id)
                  ->where('is_gaji', true);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('kategori_biaya', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('tanggal', $request->date);
        }

        $expenses = $query->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();

        return view('admin.expenses.index', compact('expenses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        
        // Find cleaner / staff roles
        $cleaners = User::whereHas('role', function($q) {
            $q->whereIn('name', ['Cleaner', 'Staff', 'Admin']);
        })->where('status', 'active')->orderBy('name')->get();

        // Get unpaid assignments grouped by user
        $unpaidAssignments = \App\Models\OrderAssignment::with(['order', 'cleaner'])
            ->where('status_gaji', 'belum_dibayar')
            ->where('gaji', '>', 0)
            ->get()
            ->groupBy('user_id');

        return view('admin.expenses.create', compact('users', 'cleaners', 'unpaidAssignments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->input('tab_type') === 'gaji') {
            $request->validate([
                'tanggal' => 'required|date',
                'cleaner_id' => 'required|exists:users,id',
                'assignment_ids' => 'required|array|min:1',
                'assignment_ids.*' => 'exists:order_assignments,id',
                'keterangan' => 'nullable|string',
            ]);

            $cleaner = User::findOrFail($request->cleaner_id);
            $assignments = \App\Models\OrderAssignment::whereIn('id', $request->assignment_ids)
                ->where('user_id', $cleaner->id)
                ->get();

            $totalGaji = $assignments->sum('gaji');

            \DB::beginTransaction();
            try {
                // Create salary expense record
                $expense = Expense::create([
                    'tanggal' => $request->tanggal,
                    'kategori_biaya' => 'Gaji / Pembayaran Jasa Cleaner',
                    'jumlah' => $totalGaji,
                    'keterangan' => $request->keterangan ?: "Pembayaran Gaji Cleaner: {$cleaner->name}",
                    'user_id' => $cleaner->id, // pelaksana / penerima
                    'is_gaji' => true,
                ]);

                // Update assignments status & link to expense
                \App\Models\OrderAssignment::whereIn('id', $request->assignment_ids)
                    ->update([
                        'status_gaji' => 'sudah_dibayar',
                        'expense_id' => $expense->id,
                    ]);

                \DB::commit();
                return redirect()->route('admin.expenses.index')->with('success', 'Slip gaji & pembayaran berhasil dibuat.');
            } catch (\Exception $e) {
                \DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['error' => 'Gagal membuat pembayaran gaji: ' . $e->getMessage()]);
            }
        }

        $request->validate([
            'tanggal' => 'required|date',
            'kategori_biaya' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        Expense::create([
            'tanggal' => $request->tanggal,
            'kategori_biaya' => $request->kategori_biaya,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user_id,
            'is_gaji' => false,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        $expense->load(['user', 'orderAssignments.order.customer']);
        return view('admin.expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Expense $expense)
    {
        $users = User::where('status', 'active')->orderBy('name')->get();
        return view('admin.expenses.edit', compact('expense', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori_biaya' => 'required|string|max:255',
            'jumlah' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $expense->update([
            'tanggal' => $request->tanggal,
            'kategori_biaya' => $request->kategori_biaya,
            'jumlah' => $request->jumlah,
            'keterangan' => $request->keterangan,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('admin.expenses.index')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        \DB::beginTransaction();
        try {
            // Restore unpaid status to linked assignments if this was a salary slip
            if ($expense->is_gaji) {
                \App\Models\OrderAssignment::where('expense_id', $expense->id)
                    ->update([
                        'status_gaji' => 'belum_dibayar',
                        'expense_id' => null,
                    ]);
            }
            $expense->delete();
            \DB::commit();
            return redirect()->route('admin.expenses.index')->with('success', 'Data pengeluaran berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus pengeluaran: ' . $e->getMessage()]);
        }
    }

    public function downloadSlip(Expense $expense)
    {
        if (!$expense->is_gaji) {
            return redirect()->back()->withErrors(['error' => 'Dokumen ini bukan merupakan slip pembayaran gaji.']);
        }

        $expense->load(['user', 'orderAssignments.order.customer', 'orderAssignments.order.items.service']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.expenses.slip', compact('expense'));
        
        $pdf->setPaper('a5', 'landscape');
        
        return $pdf->download('Slip-Gaji-' . $expense->user->name . '-' . $expense->tanggal->format('Ymd') . '.pdf');
    }
}
