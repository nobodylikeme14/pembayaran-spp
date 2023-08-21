@extends('errors.master')

@section('page_name', 'Page Not Found')

@section('error-code', 404)

@section('error-text')
<p>
    Harap periksa URL untuk memastikan tidak ada kesalahan, atau mungkin halaman telah dipindahkan atau dihapus.
</p>
<a href="{{route('dashboard')}}" class="btn mb-2 px-0 text-dark font-weight-bold">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
@endsection