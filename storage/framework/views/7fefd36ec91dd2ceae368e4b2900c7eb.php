<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Nota <?php echo e($order->order_number); ?></title>
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
            font-size: 20px;
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
            width: 75px;
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
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .table-items th {
            background-color: #f3f4f6;
            border-bottom: 1.5px solid #d1d5db;
            color: #374151;
            font-weight: bold;
            text-align: left;
            padding: 5px 8px;
            font-size: 9.5px;
            text-transform: uppercase;
        }
        .table-items td {
            padding: 6px 8px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 9.5px;
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
            margin-bottom: 12px;
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
            padding: 3px 0;
            font-size: 9.5px;
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
            padding-top: 5px;
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
            padding: 8px 10px;
        }
        .payment-box table {
            width: 100%;
            border-collapse: collapse;
        }
        .payment-box td {
            vertical-align: top;
            padding: 0;
        }
        .payment-bank-col {
            width: 60%;
        }
        .payment-note-col {
            width: 40%;
            text-align: right;
            font-size: 8.5px;
            color: #4b5563;
        }
        .payment-title {
            font-size: 9px;
            font-weight: bold;
            color: #1e3a8a;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .payment-details {
            font-size: 9px;
            line-height: 1.3;
            color: #374151;
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
        
        /* Stamp Style */
        .stamp-container {
            position: absolute;
            top: -10px;
            left: 20px;
            width: 140px;
            height: 55px;
            z-index: 100;
            pointer-events: none;
        }
        .stamp-paid {
            width: 100%;
            height: 100%;
            border: 3.5px dashed rgba(37, 99, 235, 0.45);
            border-radius: 8px;
            color: rgba(37, 99, 235, 0.45);
            font-family: 'Georgia', serif;
            font-weight: 900;
            font-style: italic;
            font-size: 24px;
            text-align: center;
            line-height: 48px;
            text-transform: uppercase;
            transform: rotate(-12deg);
            letter-spacing: 4px;
            background-color: transparent;
            text-shadow: 1px 1px 1px rgba(255, 255, 255, 0.9);
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <table class="header-table">
            <tr>
                <td>
                    <img src="<?php echo e(public_path('header.png')); ?>" class="logo" alt="PHC Logo">
                    <h1 class="company-title">PEKANBARU HOME CLEANING</h1>
                    <p class="company-subtitle">Bersih Sepenuh Hati</p>
                </td>
                <td class="invoice-title-box">
                    <h2 class="invoice-title">INVOICE</h2>
                    <table class="invoice-meta-table" align="right">
                        <tr>
                            <td class="invoice-meta-label">No. Invoice</td>
                            <td class="invoice-meta-value">: <?php echo e(str_replace('PHC-', 'TRX-', $order->order_number)); ?></td>
                        </tr>
                        <tr>
                            <td class="invoice-meta-label">Tanggal</td>
                            <td class="invoice-meta-value">: <?php echo e($order->tanggal_order ? \Carbon\Carbon::parse($order->tanggal_order)->translatedFormat('d M Y') : \Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('d M Y')); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- Customer & Service Schedule Information -->
    <table class="info-grid">
        <tr>
            <td class="info-col">
                <div class="section-title">Informasi Pelanggan</div>
                <div class="info-value">
                    <strong><?php echo e($order->customer->nama); ?></strong><br>
                    WhatsApp: <?php echo e($order->customer->no_wa); ?><br>
                    Alamat: <?php echo e($order->alamat_pengerjaan); ?>

                </div>
            </td>
            <td class="info-col">
                <div class="section-title">Detail Layanan</div>
                <div class="info-value">
                    <?php
                        $firstItem = $order->items->first();
                        $categoryName = $firstItem && $firstItem->service && $firstItem->service->category ? $firstItem->service->category->nama : 'Daily';
                    ?>
                    Layanan: <?php echo e($categoryName); ?><br>
                    Tanggal: <?php echo e(\Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('d M Y')); ?><br>
                    Jam: <?php echo e(\Carbon\Carbon::parse($order->tanggal_jadwal)->translatedFormat('H.i')); ?> WIB - Selesai<br>
                    Qty / Durasi: 
                    <?php
                        $totalQty = $order->items->sum('qty');
                        $satuan = $firstItem ? $firstItem->satuan : 'Jam';
                    ?>
                    <?php echo e($totalQty); ?> <?php echo e($satuan); ?>

                </div>
            </td>
        </tr>
    </table>

    <!-- Services Ordered Table -->
    <table class="table-items">
        <thead>
            <tr>
                <th style="width: 55%;">Produk / Layanan</th>
                <th style="width: 15%; text-align: right;">Harga</th>
                <th style="width: 10%; text-align: center;">Qty</th>
                <th style="width: 20%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e(!empty($item->service->nama_invoice) ? $item->service->nama_invoice : $item->service->nama); ?></td>
                <td class="text-right">Rp <?php echo e(number_format($item->harga_satuan, 0, ',', '.')); ?></td>
                <td class="text-center"><?php echo e($item->qty); ?></td>
                <td class="text-right">Rp <?php echo e(number_format($item->subtotal, 0, ',', '.')); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <!-- Summary Details -->
    <table class="summary-container" style="position: relative;">
        <tr>
            <td class="summary-col" style="padding-right: 15px;">
                <!-- Payment details card -->
                <div class="payment-box">
                    <div class="payment-title">Pembayaran</div>
                    <div class="payment-details">
                        Bank : BRI<br>
                        No. Rek : 109701007029508<br>
                        a/n : MAULANA MALIK IBRAHIM HSB
                    </div>
                </div>
            </td>
            <td class="summary-col" style="padding-left: 15px; position: relative;">
                <?php if($order->status_bayar === 'paid'): ?>
                <!-- Paid Stamp Badge placed close to SUBTOTAL -->
                <div class="stamp-container">
                    <div class="stamp-paid">LUNAS</div>
                </div>
                <?php endif; ?>
                <table class="summary-table">
                    <tr>
                        <td class="summary-label">SUBTOTAL</td>
                        <td class="summary-value">Rp <?php echo e(number_format($order->total_harga, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <td class="summary-label">DISKON</td>
                        <td class="summary-value">Rp <?php echo e(number_format($order->diskon, 0, ',', '.')); ?></td>
                    </tr>
                    <tr class="grand-total-row">
                        <td class="grand-total-label">TOTAL</td>
                        <td class="grand-total-value">Rp <?php echo e(number_format($order->grand_total, 0, ',', '.')); ?></td>
                    </tr>
                    <?php if($order->status_bayar === 'unpaid'): ?>
                    <tr>
                        <td colspan="2" style="text-align: right; padding-top: 8px;">
                            <span style="font-size: 11px; font-weight: bold; color: #dc2626; border: 1px solid #dc2626; padding: 3px 6px; border-radius: 4px; display: inline-block;">STATUS: BELUM BAYAR (UNPAID)</span>
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    </table>

    <!-- Additional Note and Info -->
    <table style="width: 100%; border-collapse: collapse; margin-top: 5px;">
        <tr>
            <td style="width: 60%; font-size: 8.5px; color: #4b5563; vertical-align: top; line-height: 1.3;">
                <strong>Catatan:</strong> Pembayaran dapat dilakukan setelah layanan selesai.
            </td>
            <td style="width: 40%; font-size: 8.5px; color: #4b5563; text-align: right; vertical-align: top; line-height: 1.3;">
                Terima kasih atas kepercayaan Anda.
            </td>
        </tr>
    </table>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-phone">INFORMASI & PEMESANAN: 0823-6622-0069</div>
        <div class="footer-web">PHC (Pekanbaru Home Cleaning) • pekanbaruhomecleaning.com</div>
        <div class="footer-tagline">Bersih Rumah, Sehat Keluarga, Nyaman Setiap Hari</div>
    </div>

</body>
</html><?php /**PATH /Users/macbook/CascadeProjects/PHC/laravel/resources/views/admin/orders/invoice.blade.php ENDPATH**/ ?>