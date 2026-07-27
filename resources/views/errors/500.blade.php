@extends('errors.layout')

@section('title', '500 - Server Error')

@section('content')
@include('errors.partials.error-page', [
    'code' => 500,
    'iconSymbol' => '500',
    'title' => 'Terjadi gangguan pada server',
    'message' => 'Server tidak dapat memproses permintaan saat ini. Tim pengelola dapat memeriksa log aplikasi untuk detail teknisnya.',
])
@endsection
