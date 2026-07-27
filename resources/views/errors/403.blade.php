@extends('errors.layout')

@section('title', '403 - Akses Ditolak')

@section('content')
@include('errors.partials.error-page', [
    'code' => 403,
    'iconSymbol' => '403',
    'title' => 'Akses ditolak',
    'message' => 'Anda tidak memiliki izin untuk membuka halaman ini.',
])
@endsection
