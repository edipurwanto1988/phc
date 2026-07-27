@extends('errors.layout')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')
@include('errors.partials.error-page', [
    'code' => 404,
    'iconSymbol' => '404',
    'title' => 'Halaman tidak ditemukan',
    'message' => 'Alamat yang Anda buka tidak tersedia, sudah dipindahkan, atau tautannya tidak lagi aktif.',
])
@endsection
