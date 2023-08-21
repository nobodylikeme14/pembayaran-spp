@extends('errors.master')

@section('page_name', 'Bad Request')

@section('error-code', 400)

@section('error-text')
<p>
    Maaf, permintaan Anda tidak valid atau tidak dapat diproses oleh server.
</p>
<a href="{{route('dashboard')}}" class="btn mb-2 px-0 text-dark font-weight-bold">
    <i class="fas fa-arrow-left mr-2"></i>Kembali
</a>
@endsection