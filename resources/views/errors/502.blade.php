@extends('errors.master')

@section('page_name', 'Bad Gateway')

@section('error-code', 502)

@section('error-text')
<p>
Maaf, ada masalah saat mencoba menghubungi server, atau server yang memproses permintaan tidak merespons dengan benar.
</p>
@endsection