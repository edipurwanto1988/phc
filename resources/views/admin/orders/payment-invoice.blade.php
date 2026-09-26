<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota Pembayaran {{ $order->order_number }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-table td {
            vertical-align: top;
            padding: 0;
        }
        .logo {
            height: 35px;
            width: auto;
        }
        .company-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e3a8a;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 9px;
            color: #4b5563;
            margin: 1px 0 0 0;
            font-style: italic;
        }
        .invoice-title-box {
            text-align: right;
        }
        .invoice-title {
            font-size: 18px;
            font-weight: 800;
            color: #1e3a8a;
            margin: 0;
            line-height: 1;
        }
        .invoice-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }
        .invoice-meta-table td {
            font-size: 9.5px;
            padding: 2px 0;
            vertical-align: middle;
        }
        .invoice-meta-label {
            font-weight: bold;
            color: #4b5563;
            width: 90px;
        }
        .invoice-meta-value {
            color: #1f2937;
        }
        .section-title {
            font-size: 10px;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 12px;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }
        .info-col:last-child {
            padding-right: 0;
            padding-left: 15px;
        }
        .info-value {
            font-size: 9.5px;
            color: #1f2937;
        }
        .info-value strong {
            font-size: 10.5px;
            color: #111827;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .summary-table td {
            padding: 4px 0;
            font-size: 10px;
        }
        .summary-label {
            color: #4b5563;
        }
        .summary-value {
            font-weight: bold;
            color: #111827;
            text-align: right;
            width: 120px;
        }
        .grand-total-row td {
            border-top: 1px solid #d1d5db;
            padding-top: 6px;
        }
        .grand-total-label {
            font-size: 11px !important;
            font-weight: 800;
            color: #1e3a8a;
        }
        .grand-total-value {
            font-size: 13px !important;
            font-weight: 800;
            color: #1d4ed8;
            text-align: right;
        }
        .payment-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 10px 12px;
            margin-bottom: 12px;
        }
        .payment-title {
            font-size: 10px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .payment-details {
            font-size: 9px;
            line-height: 1.3;
            color: #374151;
        }
        .highlight-row {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 6px;
            padding: 8px 12px;
            margin-bottom: 8px;
        }
        .highlight-row table {
            width: 100%;
            border-collapse: collapse;
        }
        .highlight-row td {
            font-size: 10px;
            padding: 2px 0;
        }
        .remaining-row td {
            font-weight: 800;
            color: #dc2626;
        }
        .footer {
            margin-top: 15px;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            text-align: center;
        }
        .footer-phone {
            font-size: 10px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 2px;
        }
        .footer-web {
            font-size: 9px;
            color: #2563eb;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .footer-tagline {
            font-size: 8px;
            color: #6b7280;
            font-style: italic;
        }
    </style>
</head>
<body>

    @php
        // Hitung sisa tagihan SETELAH pembayaran ini tercatat
        // (jumlah total yang sudah dibayar sampai dengan pembayaran ini)
        $paymentsUpToNow = $order->payments->filter(function($p) use ($payment) {
            return $p->id <= $payment->id;
        });
        $paidUpToNow = $paymentsUpToNow->sum('amount');
        $remainingAfter = max(0, $order->grand_total - $paidUpToNow);

        $typeLabel = match($payment->type) {
            'down_payment' => 'DOWN PAYMENT (DP)',
            'pelunasan' => 'PELUNASAN',
            default => 'CICILAN / PARTIAL',
        };
    @endphp

    <!-- Header Section -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path('header.png') }}" class="logo" alt="PHC Logo">
                    <h1 class="company-title">PEKANBARU HOME CLEANING</h1>
                    <p class="company-subtitle">Bersih Sepenuh Hati</p>
                </td>
                <td class="invoice-title-box">
                    <h2 class="invoice-title">BUKTI PEMBAYARAN</h2>
                    <table class="invoice-meta-table" align="right">
                        <tr>
                            <td class="invoice-meta-label">No. Invoice</td>
                            <td class="invoice-meta-value">: {{ str_replace('PHC-', 'TRX-', $order->order_number) }}</td>
                        </tr>
                        <tr>
                            <td class="invoice-meta-label">Tanggal Bayar</td>
                            <td class="invoice-meta-value">: {{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d M Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Customer Information -->
    <table class="info-grid">
        <tr>
            <td class="info-col">
                <div class="section-title">Informasi Pelanggan</div>
                <div class="info-value">
                    <strong>{{ $order->customer->nama }}</strong><br>
                    WhatsApp: {{ $order->customer->no_wa }}<br>
                    Alamat: {{ $order->alamat_pengerjaan }}
                </div>
            </td>
            <td class="info-col">
                <div class="section-title">Detail Layanan</div>
                <div class="info-value">
                    @php
                        $firstItem = $order->items->first();
                        $categoryName = $firstItem && $firstItem->service && $firstItem->service->category ? $firstItem->service->category->nama : 'Daily';
                    @endphp
                    Layanan: {{ $categoryName }}<br>
                    Tanggal: {{ \Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('d M Y') }}<br>
                    Grand Total: Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Highlight: Pembayaran ini -->
    <div class="highlight-row">
        <table>
            <tr>
                <td style="color: #1e3a8a; font-weight: bold;">Jenis Pembayaran</td>
                <td style="text-align: right; font-weight: bold;">{{ $typeLabel }}</td>
            </tr>
            <tr>
                <td style="font-weight: 800; font-size: 12px; color: #15803d;">JUMLAH DIBAYAR</td>
                <td style="text-align: right; font-weight: 800; font-size: 14px; color: #15803d;">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
            </tr>
            @if($payment->notes)
            <tr>
                <td style="color: #6b7280; font-size: 9px;" colspan="2">Catatan: <br>{!! nl2br(e($payment->notes)) !!}</td>
            </tr>
            @endif
        </table>
    </div>

    <!-- Rincian & Sisa -->
    <table class="summary-table">
        <tr>
            <td class="summary-label">Grand Total</td>
            <td class="summary-value">Rp {{ number_format($order->grand_total, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="summary-label">Total Terbayar (s.d. pembayaran ini)</td>
            <td class="summary-value" style="color: #15803d;">- Rp {{ number_format($paidUpToNow, 0, ',', '.') }}</td>
        </tr>
        <tr class="grand-total-row remaining-row">
            <td class="grand-total-label" style="color: #dc2626;">SISA PEMBAYARAN</td>
            <td class="grand-total-value" style="color: #dc2626;">Rp {{ number_format($remainingAfter, 0, ',', '.') }}</td>
        </tr>
    </table>

    <!-- Payment Info -->
    <div class="payment-box">
        <div class="payment-title">Pembayaran Berikutnya</div>
        <div class="payment-details">
            Bank : BRI<br>
            No. Rek : 109701007029508<br>
            a/n : MAULANA MALIK IBRAHIM HSB
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-phone">INFORMASI & PEMESANAN: 0823-6622-0069</div>
        <div class="footer-web">PHC (Pekanbaru Home Cleaning) • pekanbaruhomecleaning.com</div>
        <div class="footer-tagline">Terima kasih atas pembayaran Anda.</div>
    </div>

</body>
</html>
