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
        $query = Order::query()->with(['customer', 'assignments' => function($q) {
            $q->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        }, 'assignments.cleaner']);

        // Limit visibility for cleaners: only show orders they are assigned to
        $user = Auth::user();
        if ($user->role && $user->role->name === 'Cleaner') {
            $query->whereHas('assignments', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

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
            'items.*.harga' => 'required|numeric|min:0',
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
                $hargaSatuan = isset($item['harga']) ? (float) $item['harga'] : (float) $service->harga;
                $subtotal = $hargaSatuan * $item['qty'];
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'service_id' => $service->id,
                    'qty' => $item['qty'],
                    'satuan' => $service->satuan,
                    'harga_satuan' => $hargaSatuan,
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
        $order->load(['customer', 'items.service', 'assignments' => function($q) {
            $q->orderBy('sort_order', 'asc')->orderBy('id', 'asc');
        }, 'assignments.cleaner', 'creator']);
        
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
            'items' => 'required|array|min:1',
            'items.*.service_id' => 'required|exists:services,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.harga' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Calculate prices and validate new items
            $totalHarga = 0.0;
            $itemsData = [];

            foreach ($request->items as $item) {
                $service = Service::findOrFail($item['service_id']);
                $hargaSatuan = (float) $item['harga'];
                $subtotal = $hargaSatuan * $item['qty'];
                $totalHarga += $subtotal;

                $itemsData[] = [
                    'service_id' => $service->id,
                    'qty' => $item['qty'],
                    'satuan' => $service->satuan,
                    'harga_satuan' => $hargaSatuan,
                    'subtotal' => $subtotal,
                    'catatan' => $item['catatan'] ?? null,
                ];
            }

            $diskon = $request->diskon ?? 0;
            $grandTotal = max(0, $totalHarga - $diskon);

            // Update main order
            $order->update([
                'tanggal_jadwal' => $request->tanggal_jadwal,
                'alamat_pengerjaan' => $request->alamat_pengerjaan,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'total_harga' => $totalHarga,
                'diskon' => $diskon,
                'grand_total' => $grandTotal,
                'metode_bayar' => $request->metode_bayar,
                'catatan' => $request->catatan,
            ]);

            // Sync items: Delete old ones and insert new ones
            $order->items()->delete();
            foreach ($itemsData as $itemData) {
                $order->items()->create($itemData);
            }

            DB::commit();
            return redirect()->route('admin.orders.show', $order)->with('success', 'Order berhasil diperbarui.');
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

        // Check if the cleaner is already assigned to this order
        $exists = OrderAssignment::where('order_id', $order->id)
            ->where('user_id', $request->cleaner_id)
            ->exists();

        if ($exists) {
            return redirect()->route('admin.orders.show', $order)->withErrors(['error' => 'Cleaner ini sudah ditugaskan ke order ini.']);
        }

        // For simplicity, we create a new cleaner assignment
        $maxSort = OrderAssignment::where('order_id', $order->id)->max('sort_order') ?? 0;
        OrderAssignment::create([
            'order_id' => $order->id,
            'user_id' => $request->cleaner_id,
            'status' => 'assigned',
            'sort_order' => $maxSort + 1,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Cleaner berhasil ditugaskan ke order ini.');
    }

    public function updateGaji(Request $request, OrderAssignment $assignment)
    {
        $request->validate([
            'gaji' => 'required|numeric|min:0',
            'status_gaji' => 'required|in:belum_dibayar,sudah_dibayar',
        ]);

        $assignment->update([
            'gaji' => $request->gaji,
            'status_gaji' => $request->status_gaji,
        ]);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Informasi gaji cleaner berhasil diperbarui.',
                'data' => [
                    'gaji' => $assignment->gaji,
                    'status_gaji' => $assignment->status_gaji
                ]
            ]);
        }

        return redirect()->route('admin.orders.show', $assignment->order_id)->with('success', 'Informasi gaji cleaner berhasil diperbarui.');
    }

    public function uploadPhotos(Request $request, OrderAssignment $assignment)
    {
        $request->validate([
            'foto_sebelum' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'foto_sesudah' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $data = [];
        $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';

        if ($request->hasFile('foto_sebelum')) {
            if ($assignment->foto_sebelum && !str_starts_with($assignment->foto_sebelum, 'http') && file_exists(public_path($assignment->foto_sebelum))) {
                @unlink(public_path($assignment->foto_sebelum));
            }
            $file = $request->file('foto_sebelum');
            $filename = 'before_' . $assignment->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            if ($gdriveConnected) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $result = $driveService->uploadFile($file->getRealPath(), $filename, $assignment->order->order_number);
                    $data['foto_sebelum'] = $result['web_content_link'];
                } catch (\Exception $e) {
                    \Log::error("Failed to upload before-photo to GDrive: " . $e->getMessage());
                    // Fallback to local
                    $file->move(public_path('uploads/orders'), $filename);
                    $data['foto_sebelum'] = 'uploads/orders/' . $filename;
                }
            } else {
                $file->move(public_path('uploads/orders'), $filename);
                $data['foto_sebelum'] = 'uploads/orders/' . $filename;
            }
        }

        if ($request->hasFile('foto_sesudah')) {
            if ($assignment->foto_sesudah && !str_starts_with($assignment->foto_sesudah, 'http') && file_exists(public_path($assignment->foto_sesudah))) {
                @unlink(public_path($assignment->foto_sesudah));
            }
            $file = $request->file('foto_sesudah');
            $filename = 'after_' . $assignment->id . '_' . time() . '.' . $file->getClientOriginalExtension();

            if ($gdriveConnected) {
                try {
                    $driveService = new \App\Services\GoogleDriveService();
                    $result = $driveService->uploadFile($file->getRealPath(), $filename, $assignment->order->order_number);
                    $data['foto_sesudah'] = $result['web_content_link'];
                } catch (\Exception $e) {
                    \Log::error("Failed to upload after-photo to GDrive: " . $e->getMessage());
                    // Fallback to local
                    $file->move(public_path('uploads/orders'), $filename);
                    $data['foto_sesudah'] = 'uploads/orders/' . $filename;
                }
            } else {
                $file->move(public_path('uploads/orders'), $filename);
                $data['foto_sesudah'] = 'uploads/orders/' . $filename;
            }
        }

        if (!empty($data)) {
            $assignment->update($data);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Foto pekerjaan berhasil diunggah.',
                'data' => $data
            ]);
        }

        return redirect()->route('admin.orders.show', $assignment->order_id)->with('success', 'Foto pekerjaan berhasil diunggah.');
    }

    public function deletePhoto(OrderAssignment $assignment, string $type)
    {
        if (!in_array($type, ['foto_sebelum', 'foto_sesudah'])) {
            return abort(400);
        }

        $photoPath = $assignment->{$type};
        if ($photoPath && !str_starts_with($photoPath, 'http') && file_exists(public_path($photoPath))) {
            @unlink(public_path($photoPath));
        }

        $assignment->update([$type => null]);

        return redirect()->route('admin.orders.show', $assignment->order_id)->with('success', 'Foto berhasil dihapus.');
    }

    public function viewDrivePhoto(OrderAssignment $assignment, string $type)
    {
        if (!in_array($type, ['foto_sebelum', 'foto_sesudah'])) {
            return abort(400);
        }

        $photoUrl = $assignment->{$type};
        if (!$photoUrl) {
            return abort(404);
        }

        // If it's a local file, redirect directly or serve it
        if (!str_starts_with($photoUrl, 'http')) {
            return response()->file(public_path($photoUrl));
        }

        try {
            // Serve the Google Drive image via Laravel backend (Proxy)
            // By requesting it server-side, we completely bypass CORS / OpaqueResponseBlocking
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $photoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
            
            // If Google Drive requires auth, we could attach OAuth token here
            $gdriveConnected = \App\Models\Setting::get('gdrive_connected') === 'true';
            if ($gdriveConnected) {
                $tokenJson = \App\Models\Setting::get('gdrive_access_token');
                if ($tokenJson) {
                    $accessToken = json_decode($tokenJson, true);
                    if (isset($accessToken['access_token'])) {
                        curl_setopt($ch, CURLOPT_HTTPHEADER, [
                            'Authorization: Bearer ' . $accessToken['access_token']
                        ]);
                    }
                }
            }

            $imageData = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
            curl_close($ch);

            if ($httpCode === 200 && $imageData) {
                return response($imageData)->header('Content-Type', $contentType ?: 'image/jpeg');
            }

            return abort(404, 'Gagal mengambil gambar dari Google Drive.');
        } catch (\Exception $e) {
            \Log::error("Image Proxy error: " . $e->getMessage());
            return abort(500);
        }
    }

    public function reorderAssignments(Request $request, Order $order)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:order_assignments,id',
        ]);

        foreach ($request->ids as $index => $id) {
            OrderAssignment::where('id', $id)
                ->where('order_id', $order->id)
                ->update(['sort_order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Urutan cleaner / PIC berhasil diperbarui.'
        ]);
    }

    public function deleteAssignment(OrderAssignment $assignment)
    {
        $orderId = $assignment->order_id;
        $assignment->delete();

        // Re-adjust sort order for remaining assignments
        $remaining = OrderAssignment::where('order_id', $orderId)->orderBy('sort_order', 'asc')->get();
        foreach ($remaining as $index => $assign) {
            $assign->update(['sort_order' => $index]);
        }

        return redirect()->route('admin.orders.show', $orderId)->with('success', 'Cleaner berhasil dihapus dari tugas order ini.');
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

    public function updateCoordinates(Request $request, Order $order)
    {
        $request->validate([
            'latitude'  => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $order->update([
            'latitude'  => $request->latitude ?: null,
            'longitude' => $request->longitude ?: null,
        ]);

        return redirect()->route('admin.orders.show', $order)->with('success', 'Koordinat lokasi berhasil disimpan.');
    }

    public function downloadInvoice(Order $order)
    {
        $order->load(['customer', 'items.service']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.invoice', compact('order'));
        
        // Custom paper size: 140mm width by 210mm height or A5
        $pdf->setPaper('a5', 'portrait');
        
        return $pdf->download('Nota-' . $order->order_number . '.pdf');
    }
}
