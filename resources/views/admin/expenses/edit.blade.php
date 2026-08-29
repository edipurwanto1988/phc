@extends('layouts.admin')

@section('title', 'Ubah Pengeluaran')
@section('header')
<i class="ri-money-dollar-circle-line"></i> Ubah Data Pengeluaran
@endsection

@section('content')
<div class="card p-6 bg-white rounded-xl shadow-sm border border-gray-200">
    <form method="POST" action="{{ route('admin.expenses.update', $expense) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Tanggal -->
            <div>
                <label for="tanggal" class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Pengeluaran</label>
                <input type="date" name="tanggal" id="tanggal" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" value="{{ $expense->tanggal->format('Y-m-d') }}" required>
            </div>

            <!-- Pelaksana (User) -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-1">Pelaksana / Penanggung Jawab</label>
                <select name="user_id" id="user_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm bg-white" required>
                    <option value="">-- Pilih Pelaksana --</option>
                    @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ old('user_id', $expense->user_id) == $user->id ? 'selected' : '' }}>
                        {{ $user->name }} ({{ $user->role->name ?? 'User' }})
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Kategori Biaya -->
            <div>
                <label for="kategori_biaya" class="block text-sm font-semibold text-gray-700 mb-1">Kategori Biaya</label>
                <input type="text" name="kategori_biaya" id="kategori_biaya" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: Pembelian Bahan, Uang Makan, Transportasi" value="{{ $expense->kategori_biaya }}" required>
            </div>

            <!-- Jumlah Uang -->
            <div>
                <label for="jumlah" class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Biaya / Pengeluaran (Rp)</label>
                <input type="number" name="jumlah" id="jumlah" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Contoh: 75000" min="0" value="{{ (int)$expense->jumlah }}" required>
            </div>
        </div>

        <!-- Keterangan -->
        <div class="mb-6">
            <label for="keterangan" class="block text-sm font-semibold text-gray-700 mb-1">Keterangan Tambahan</label>
            <textarea name="keterangan" id="keterangan" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-sm" placeholder="Instruksi / Rincian detail pengeluaran...">{{ $expense->keterangan }}</textarea>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
            <button type="submit" class="btn bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm transition-all shadow-sm">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.expenses.index') }}" class="btn border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium px-5 py-2.5 rounded-lg text-sm transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection