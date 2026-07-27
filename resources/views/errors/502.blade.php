@extends('errors.layout')

@section('title', '502 - Bad Gateway')

@section('content')
@include('errors.partials.error-page', [
    'code' => 502,
    'iconSymbol' => '502',
    'title' => 'Koneksi server bermasalah',
    'message' => 'Server menerima respons yang tidak valid dari layanan lain. Silakan coba beberapa saat lagi.',
])
@endsection
