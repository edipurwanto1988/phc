@extends('errors.layout')

@section('title', '429 - Terlalu Banyak Permintaan')

@section('content')
@include('errors.partials.error-page', [
    'code' => 429,
    'iconSymbol' => '429',
    'title' => 'Terlalu banyak permintaan',
    'message' => 'Permintaan dari perangkat Anda terlalu sering. Tunggu sebentar lalu coba lagi.',
])
@endsection
