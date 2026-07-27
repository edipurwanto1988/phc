@extends('errors.layout')

@section('title', '419 - Sesi Berakhir')

@section('content')
@include('errors.partials.error-page', [
    'code' => 419,
    'iconSymbol' => '419',
    'title' => 'Sesi sudah berakhir',
    'message' => 'Sesi formulir Anda sudah kedaluwarsa. Silakan kembali dan muat ulang halaman sebelum mencoba lagi.',
])
@endsection
