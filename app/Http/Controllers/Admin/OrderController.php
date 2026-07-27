<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderAssignment;
use App\Models\Customer;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with(['customer', 'assignments.cleaner']);

        // Search by Order Number or Customer Name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($cQ) use ($search) {
                      $cQ->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Tanggal
        if ($request->filled('date')) {
            $query->whereDate('tanggal_jadwal', $request->date);
        }

        $orders = $query->orderBy('tanggal_jadwal', 'desc')->paginate(10)->withQueryString();

        return view('admin.orders.index', compact('orders'));
    }

    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('nama')->get();
        $services = Service::where('is_active', true)->orderBy('nama')->get();
        $cleaners = User::whereHas('role', function($q) {
            $q->where('name', 'Cleaner');
        })->where('status', 'active')->orderBy('name')->get();

        return view('admin.orders.create', compact('customers', 'services', 'cleaners'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'tanggal_jadwal' => 'required|date_format:Y-m-d\TH:i',
            'alamat_pengerjaan' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.catatan' => 'nullable|string',
            'diskon' => 'nullable|numeric|min:0',
            'metode_bayar' => 'required|string',
            'status_bayar' => 'required|in:unpaid,partial,paid',
            'cleaner_id' => 'nullable|exists:users,id',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Calculate prices
            $totalHarga = 0.0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $service = Service::findOrFail($item['service_id']);
                $subtotal = $service->harga * $item['qty'];
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'service_id' => $service->id,
                    'qty' => $item['qty'],
                    'satuan' => $service->satuan,
                    'harga_satuan' => $service->harga,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                ];
            }

            $diskon = $request->diskon ?? 0;
            $grandTotal = max(0, $totalHarga - $diskon);

            // Generate Order Number: PHC-YYYYMMDD-SEQ
            $today = now()->format('Ymd');
            $todayCount = Order::whereDate('created_at', now()->toDateString())->count();
            $seq = str_pad($todayCount + 1, 3, '0', STR_PAD_LEFT);
            $orderNumber = "PHC-{$today}-{$seq}";

            // Create Order
            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'tanggal_order' => now()->toDateString(),
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'alamat_pengerjaan' => $request->alamat_pengerjaan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'metode_bayar' => $request->metode_bayar,
                'status_bayar' => $request->status_bayar,
                'catatan' => $request->catatan,
                'created_by' => Auth::id(),
            ]);

            // Save Items
            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
            }

            // Assign Cleaner if selected
            if ($request->filled('cleaner_id')) {
                OrderAssignment::create([
                    'order_id' => $order->id,
                    'user_id' => $request->cleaner_id,
                    'status' => 'assigned',
                ]);
            }

            DB::commit();
            return redirect()->route('admin.orders.index')->with('success', "Order {$orderNumber} berhasil dibuat.");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal membuat order: ' . $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        $order->load(['customer', 'items.service', 'assignments.cleaner', 'creator']);
        
        $cleaners = User::whereHas('role', function($q) {
            $q->where('name', 'Cleaner');
        })->where('status', 'active')->orderBy('name')->get();

        return view('admin.orders.show', compact('order', 'cleaners'));
    }

    public function edit(Order $order)
    {
        $order->load(['items.service']);
        $customers = Customer::where('status', 'active')->orderBy('nama')->get();
        $services = Service::where('is_active', true)->orderBy('nama')->get();

        return view('admin.orders.edit', compact('order', 'customers', 'services'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'tanggal_jadwal' => 'required|date_format:Y-m-d\TH:i',
            'alamat_pengerjaan' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'diskon' => 'nullable|numeric|min:0',
            'metode_bayar' => 'required|string',
            'catatan' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $diskon = $request->diskon ?? 0;
            $grandTotal = max(0, $order->total_harga - $diskon);

            $order->update([
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'alamat_pengerjaan' => $request->alamat_pengerjaan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'metode_bayar' => $request->metode_bayar,
                'catatan' => $request->catatan,
            ]);

            DB::commit();
            return redirect()->route('admin.orders.show', $order)->with('success', 'Order metadata berhasil diubah.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => 'Gagal mengubah order: ' . $e->getMessage()]);
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order berhasil dihapus.');
    }

    public function assignCleaner(Request $request, Order $order)
    {
        $request->validate([
            'cleaner_id' => 'required|exists:users,id',
        ]);

        // Mark existing cleaner assignments as cancelled or delete them, or support multiple cleaners
        // For simplicity, we create a new cleaner assignment
        OrderAssignment::create([
            'order_id' => $order->id,
            'user_id' => $request->cleaner_id,
            'status' => 'assigned',
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Cleaner berhasil ditugaskan ke order ini.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'nullable|in:pending,confirmed,in_progress,completed,cancelled',
            'status_bayar' => 'nullable|in:unpaid,partial,paid',
        ]);

        if ($request->filled('status')) {
            $order->status = $request->status;

            // Sync cleaner assignment status if needed
            if ($request->status === 'completed') {
                $order->assignments()->where('status', '!=', 'done')->update([
                    'status' => 'done',
                    'finished_at' => now(),
                ]);
            } elseif ($request->status === 'in_progress') {
                $order->assignments()->where('status', 'assigned')->update([
                    'status' => 'working',
                    'started_at' => now(),
                ]);
            }
        }

        if ($request->filled('status_bayar')) {
            $order->status_bayar = $request->status_bayar;
        }

        $order->save();

        return redirect()->route('admin.orders.show', $order)->with('success', 'Status order / pembayaran berhasil diperbarui.');
    }
}
