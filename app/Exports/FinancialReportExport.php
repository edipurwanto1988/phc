<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection(): \Illuminate\Support\Collection
    {
        $query = Order::with(['payments']);

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Order ID',
            'Tanggal',
            'Total Tagihan',
            'Total Dibayar',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->order_number ?? $order->id,
            $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : '-',
            $order->total_price,
            $order->payments ? $order->payments->sum('amount') : 0,
            $order->status,
        ];
    }
}
