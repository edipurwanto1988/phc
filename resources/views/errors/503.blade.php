@extends('errors.layout')

@section('title', '503 - Layanan Tidak Tersedia')

@section('content')
@include('errors.partials.error-page', [
    'code' => 503,
    'iconSymbol' => '503',
    'title' => 'Layanan sementara tidak tersedia',
    'message' => 'Website sedang dalam pemeliharaan atau beban server sedang tinggi. Silakan coba lagi nanti.',
])
@endsection
