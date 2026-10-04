<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;
    protected $status;

    public function __construct($startDate = null, $endDate = null, $status = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->status = $status;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): \Illuminate\Support\Collection
    {
        $query = Order::with(['customer', 'items.service']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }
        if ($this->status && $this->status !== 'all') {
            $query->where('status', $this->status);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Tanggal Order',
            'Pelanggan',
            'Layanan',
            'Status',
            'Total Harga',
            'Catatan',
        ];
    }

    public function map($order): array
    {
        $services = $order->items->map(function($item) {
            return $item->service ? $item->service->nama : '-';
        })->implode(', ');

        return [
            $order->order_number ?? $order->id,
            $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
            $order->customer ? $order->customer->name : '-',
            $services,
            $order->status,
            $order->total_price,
            $order->catatan ?? '-',
        ];
    }
}
