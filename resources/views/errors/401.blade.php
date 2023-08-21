@extends('errors.master')

@section('page_name', 'Unauthorized')

@section('error-code', 401)

@section('error-text')
<p>
    Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Harap masuk terlebih dahulu atau berikan kredensial yang valid.
</p>
<a href="{{route('dashboard')}}" class="btn mb-2 px-0 text-dark font-weight-bold">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
@endsection