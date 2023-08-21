@extends('errors.master')

@section('page_name', 'Internal Server Error')

@section('error-code', 500)

@section('error-text')
<p>
Maaf, terjadi kesalahan pada server saat mengolah permintaan Anda.
</p>
@endsection