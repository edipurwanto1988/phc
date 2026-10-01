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

        $firstItem = $order->items->first();
        $categoryName = $firstItem && $firstItem->service && $firstItem->service->category ? $firstItem->service->category->nama : 'Daily';

        function penyebut($nilai) {
            $nilai = abs($nilai);
            $huruf = array("", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
            $temp = "";
            if ($nilai < 12) {
                $temp = " ". $huruf[$nilai];
            } else if ($nilai <20) {
                $temp = penyebut($nilai - 10). " belas";
            } else if ($nilai < 100) {
                $temp = penyebut($nilai/10)." puluh". penyebut($nilai % 10);
            } else if ($nilai < 200) {
                $temp = " seratus" . penyebut($nilai - 100);
            } else if ($nilai < 1000) {
                $temp = penyebut($nilai/100) . " ratus" . penyebut($nilai % 100);
            } else if ($nilai < 2000) {
                $temp = " seribu" . penyebut($nilai - 1000);
            } else if ($nilai < 1000000) {
                $temp = penyebut($nilai/1000) . " ribu" . penyebut($nilai % 1000);
            } else if ($nilai < 1000000000) {
                $temp = penyebut($nilai/1000000) . " juta" . penyebut($nilai % 1000000);
            }
            return $temp;
        }
        function terbilang($nilai) {
            if($nilai<0) {
                $hasil = "minus ". trim(penyebut($nilai));
            } else {
                $hasil = trim(penyebut($nilai));
            }
            return ucwords($hasil) . " Rupiah";
        }
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

    <!-- Main Kwitansi Box -->
    <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; margin-top: 15px;">
        <tr>
            <td style="width: 20%; padding: 8px 10px; border-bottom: 1px solid #000; font-size: 11px; vertical-align: top;">
                Sudah Terima Dari<br>
                <span style="font-size: 10px; font-style: italic; color: #333;">Received From</span>
            </td>
            <td style="width: 2%; padding: 8px 0; border-bottom: 1px solid #000; font-size: 11px; vertical-align: top;">:</td>
            <td style="width: 78%; padding: 8px 10px; border-bottom: 1px solid #000; font-size: 11px; font-weight: bold; vertical-align: top;">
                <div style="border-bottom: 1.5px dotted #333; padding-bottom: 2px;">{{ $order->customer->nama }}</div>
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 10px; border-bottom: 1px solid #000; font-size: 11px; vertical-align: top;">
                Banyaknya Uang<br>
                <span style="font-size: 10px; font-style: italic; color: #333;">Amount Received</span>
            </td>
            <td style="padding: 8px 0; border-bottom: 1px solid #000; font-size: 11px; vertical-align: top;">:</td>
            <td style="padding: 8px 10px; border-bottom: 1px solid #000; font-size: 11px; font-weight: bold; font-style: italic; vertical-align: top;">
                @php
                    $terbilangText = terbilang($payment->amount);
                    $terbilangLines = explode("\n", wordwrap($terbilangText, 65, "\n"));
                @endphp
                @foreach($terbilangLines as $line)
                <div style="border-bottom: 1.5px dotted #333; padding-bottom: 2px; margin-bottom: 4px; width: 100%;">{{ $line }}</div>
                @endforeach
            </td>
        </tr>
        <tr>
            <td style="padding: 8px 10px; font-size: 11px; vertical-align: top;">
                Untuk Pembayaran<br>
                <span style="font-size: 10px; font-style: italic; color: #333;">In Payment Of</span>
            </td>
            <td style="padding: 8px 0; font-size: 11px; vertical-align: top;">:</td>
            <td style="padding: 8px 10px; font-size: 11px; vertical-align: top;">
                @php
                    $paymentDesc = "Pembayaran {$typeLabel} untuk layanan {$categoryName} tanggal " . \Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('d F Y') . " di alamat {$order->alamat_pengerjaan}.";
                    $descLines = explode("\n", wordwrap($paymentDesc, 65, "\n"));
                @endphp
                @foreach($descLines as $line)
                <div style="border-bottom: 1.5px dotted #333; padding-bottom: 2px; margin-bottom: 5px; width: 100%;">{{ $line }}</div>
                @endforeach
            </td>
        </tr>
    </table>

    <!-- Bottom Section -->
    <table style="width: 100%; margin-top: 15px; page-break-inside: avoid;">
        <tr>
            <td style="width: 60%; vertical-align: top;">
                <table style="border-collapse: collapse; margin-top: 5px;">
                    <tr>
                        <td style="font-size: 16px; font-weight: bold; padding-right: 8px;">Rp.</td>
                        <td style="font-size: 16px; font-weight: bold; border-top: 3px double #000; border-bottom: 3px double #000; padding: 5px 25px; background: repeating-linear-gradient(0deg, transparent, transparent 1px, #f3f4f6 1px, #f3f4f6 2px);">
                            {{ number_format($payment->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                </table>

                <div style="margin-top: 25px; font-size: 9.5px; line-height: 1.3;">
                    <strong>Catatan :</strong><br>
                    1. Mohon pembayaran ditransfer ke rekening bank berikut ini :<br>
                       &nbsp;&nbsp;&nbsp;<strong>Bank BRI</strong><br>
                       &nbsp;&nbsp;&nbsp;<strong>No. Rek: 109701007029508</strong><br>
                       &nbsp;&nbsp;&nbsp;<strong>a/n: MAULANA MALIK IBRAHIM HSB</strong><br>
                    2. Pembayaran baru dianggap sah setelah dana masuk ke rekening.<br>
                    @if($payment->notes)
                    3. {{ $payment->notes }}
                    @endif
                </div>
            </td>
            <td style="width: 40%; vertical-align: top; text-align: center;">
                <p style="margin-bottom: 5px; font-size: 10px;">Pekanbaru, {{ \Carbon\Carbon::parse($payment->payment_date)->translatedFormat('d F Y') }}</p>
                <div style="position: relative; width: 120px; height: 35px; margin: 0 auto;">
                    <img src="{{ public_path('images/stempel_phc.png') }}" style="position: absolute; left: -30px; top: -10px; width: 96px; opacity: 0.8; z-index: -1; transform: rotate(-8deg);" alt="Stempel">
                    <img src="{{ public_path('images/tdd_dian.png') }}" style="position: absolute; left: 10px; top: -5px; width: 63px; z-index: 2;" alt="Tanda Tangan">
                </div>
                <p style="margin-top: 0px; font-size: 11px; font-weight: bold; text-decoration: underline; color: #000; margin-bottom: 2px; position: relative; z-index: 5;">Dian Anggara</p>
                <p style="margin-top: 0; font-size: 9px; color: #333;">Chief Executive Officer</p>
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-phone">INFORMASI & PEMESANAN: 0823-6622-0069</div>
        <div class="footer-web">PHC (Pekanbaru Home Cleaning) • pekanbaruhomecleaning.com</div>
        <div class="footer-tagline">Terima kasih atas pembayaran Anda.</div>
    </div>

</body>
</html>
