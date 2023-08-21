@extends('layouts.auth.master')

@section('page_name', 'Lupa Password')

@section('body_class', 'bg-reset-image')

@section('page_script')
<script src="{{asset('assets/js/auth/lupa-password-script.js')}}"></script>
@endsection

@section('content')
<form class="needs-validation user" name="form-kirim-link" action="{{route('lupa_passwordPost')}}" novalidate>
    <a href="{{route('login')}}" class="btn btn-sm mb-2 p-0 text-dark font-weight-bold">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <div class="card border-left-danger text-dark mb-3">
        <div class="card-body">
            Silahkan masukkan alamat email anda, dan kami akan mengirimkan link reset password untuk mereset password akun anda.
        </div>
    </div>
    <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control form-control-user mb-1" name="email" placeholder="Email" autofocus required>
    </div>
    <div class="mt-5 mb-3">
        <button type="submit" name="submit" class="btn btn-danger btn-user btn-block text-uppercase font-weight-bold">
            Kirim Link<i class="fas fa-paper-plane ml-1"></i>
        </button>
    </div>
</form>
@endsection