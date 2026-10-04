@extends('layouts.admin')

@section('title', 'Download Data')

@section('content')
<div class="p-6">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Download Data</h1>
            <p class="text-sm text-gray-500 mt-1">Export data ke format Excel dengan filter tanggal dan status.</p>
        </div>
    </div>

    <div class="flex flex-col gap-6">
        
        <!-- Data Pelanggan -->
        <div id="pelanggan" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i class="ri-user-smile-line text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Data Pelanggan</h3>
            </div>
            <form action="{{ route('admin.exports.customers') }}" method="GET" class="p-5 flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <i class="ri-file-excel-2-line"></i> Download
                </button>
            </form>
        </div>

        <!-- Data Transaksi -->
        <div id="transaksi" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                    <i class="ri-shopping-cart-2-line text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Data Transaksi</h3>
            </div>
            <form action="{{ route('admin.exports.orders') }}" method="GET" class="p-5 flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        <option value="all">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="w-full md:w-auto bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-6 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <i class="ri-file-excel-2-line"></i> Download
                </button>
            </form>
        </div>

        <!-- Laporan Keuangan -->
        <div id="keuangan" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <i class="ri-money-dollar-circle-line text-xl"></i>
                </div>
                <h3 class="font-semibold text-gray-800">Laporan Keuangan</h3>
            </div>
            <form action="{{ route('admin.exports.financials') }}" method="GET" class="p-5 flex flex-col md:flex-row items-end gap-4">
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="start_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <div class="flex-1 w-full">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="end_date" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                </div>
                <button type="submit" class="w-full md:w-auto bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-6 rounded-lg flex items-center justify-center gap-2 transition-colors">
                    <i class="ri-file-excel-2-line"></i> Download
                </button>
            </form>
        </div>

    </div>
</div>
@endsection
