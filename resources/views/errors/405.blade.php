@extends('errors.master')

@section('page_name', 'Method Not Allowed')

@section('error-code', 405)

@section('error-text')
<p>
    Maaf, metode yang Anda gunakan tidak diizinkan untuk akses halaman ini.
</p>
<a href="{{route('dashboard')}}" class="btn mb-2 px-0 text-dark font-weight-bold">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
@endsection