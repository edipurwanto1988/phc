<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji {{ $expense->user->name }}</title>
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header {
            border-bottom: 2px solid #10b981;
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
            height: 30px;
            width: auto;
        }
        .company-title {
            font-size: 11px;
            font-weight: bold;
            color: #047857;
            margin: 0;
            letter-spacing: 0.5px;
        }
        .company-subtitle {
            font-size: 8px;
            color: #4b5563;
            margin: 1px 0 0 0;
            font-style: italic;
        }
        .slip-title-box {
            text-align: right;
        }
        .slip-title {
            font-size: 16px;
            font-weight: 850;
            color: #047857;
            margin: 0;
            line-height: 1;
        }
        .slip-meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }
        .slip-meta-table td {
            font-size: 8.5px;
            padding: 1px 0;
        }
        .slip-meta-label {
            font-weight: bold;
            color: #4b5563;
            width: 70px;
        }
        .slip-meta-value {
            color: #1f2937;
        }
        .section-title {
            font-size: 9px;
            font-weight: 800;
            color: #059669;
            text-transform: uppercase;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 3px;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 10px;
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
            font-size: 8.5px;
            color: #1f2937;
        }
        .info-value strong {
            font-size: 9.5px;
            color: #111827;
        }
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .table-items th {
            background-color: #f3f4f6;
            border-bottom: 1.5px solid #d1d5db;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 4px 6px;
            font-size: 8.5px;
            text-transform: uppercase;
        }
        .table-items td {
            padding: 5px 6px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 8.5px;
            color: #1f2937;
            vertical-align: middle;
        }
        .table-items tr:last-child td {
            border-bottom: 1.5px solid #d1d5db;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary-container {
            width: 100%;
            margin-top: 5px;
        }
        .summary-col {
            width: 50%;
            vertical-align: top;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }
        .summary-table td {
            padding: 2px 0;
            font-size: 8.5px;
        }
        .summary-label {
            color: #4b5563;
        }
        .summary-value {
            font-weight: bold;
            color: #111827;
            text-align: right;
            width: 100px;
        }
        .grand-total-row td {
            border-top: 1px solid #d1d5db;
            padding-top: 4px;
        }
        .grand-total-label {
            font-size: 10px !important;
            font-weight: 800;
            color: #047857;
        }
        .grand-total-value {
            font-size: 12px !important;
            font-weight: 800;
            color: #059669;
            text-align: right;
        }
        .signature-section {
            margin-top: 15px;
            width: 100%;
            border-collapse: collapse;
        }
        .signature-col {
            width: 50%;
            text-align: center;
            font-size: 8.5px;
            color: #4b5563;
            vertical-align: top;
        }
        .signature-space {
            height: 35px;
        }
        .signature-name {
            font-weight: bold;
            color: #111827;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <img src="{{ public_path('header.png') }}" class="logo" alt="PHC Logo">
                    <h1 class="company-title">PEKANBARU HOME CLEANING</h1>
                    <p class="company-subtitle">Bersih Sepenuh Hati</p>
                </td>
                <td class="slip-title-box">
                    <h2 class="slip-title">SLIP PEMBAYARAN GAJI</h2>
                    <table class="slip-meta-table" align="right">
                        <tr>
                            <td class="slip-meta-label">No. Slip</td>
                            <td class="slip-meta-value">: SLIP-{{ str_pad($expense->id, 5, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td class="slip-meta-label">Tanggal Bayar</td>
                            <td class="slip-meta-value">: {{ $expense->tanggal->translatedFormat('d M Y') }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Cleaner Information -->
    <table class="info-grid">
        <tr>
            <td class="info-col">
                <div class="section-title">Penerima Gaji (Cleaner)</div>
                <div class="info-value">
                    Nama: <strong>{{ $expense->user->name }}</strong><br>
                    Email: {{ $expense->user->email }}<br>
                    Jabatan: {{ $expense->user->role->name ?? 'Cleaner' }}
                </div>
            </td>
            <td class="info-col">
                <div class="section-title">Metode Pembayaran</div>
                <div class="info-value">
                    Kategori: Pengeluaran Gaji Jasa Cleaner<br>
                    Status: <strong style="color: #059669;">LUNAS / SUDAH DIBAYAR</strong><br>
                    Catatan: {{ $expense->keterangan }}
                </div>
            </td>
        </tr>
    </table>

    <!-- Table of Orders Done -->
    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 25%;">No. Order</th>
                <th style="width: 30%;">Pelanggan</th>
                <th style="width: 25%;">Tanggal Pekerjaan</th>
                <th style="width: 20%; text-align: right;">Gaji (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expense->orderAssignments as $assignment)
            <tr>
                <td><strong>{{ $assignment->order->order_number }}</strong></td>
                <td>{{ $assignment->order->customer->nama }}</td>
                <td>{{ \Carbon\Carbon::parse($assignment->order->tanggal_jadwal)->translatedFormat('d M Y, H:i') }}</td>
                <td class="text-right">Rp {{ number_format($assignment->gaji, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Total Summary & Signatures -->
    <table class="summary-container">
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <table class="signature-section">
                    <tr>
                        <td class="signature-col">
                            Penerima,<br>
                            <div class="signature-space"></div>
                            <span class="signature-name">{{ $expense->user->name }}</span>
                        </td>
                        <td class="signature-col">
                            Manajer Operasional,<br>
                            <div class="signature-space"></div>
                            <span class="signature-name">Super Admin PHC</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; vertical-align: top;">
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">SUBTOTAL PENDAPATAN</td>
                        <td class="summary-value">Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="summary-label">POTONGAN / DISKON</td>
                        <td class="summary-value">Rp 0</td>
                    </tr>
                    <tr class="grand-total-row">
                        <td class="grand-total-label">TOTAL DITERIMA</td>
                        <td class="grand-total-value">Rp {{ number_format($expense->jumlah, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>