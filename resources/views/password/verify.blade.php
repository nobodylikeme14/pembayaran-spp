@extends('layouts.auth.master')

@section('page_name', 'Reset Password')

@section('body_class', 'bg-verify-image')

@section('page_script')
<script src="{{asset('assets/js/auth/lupa-password-script.js')}}"></script>
@endsection

@section('content')
<form class="needs-validation user" name="form-reset-password" action="{{route('reset_passwordPost')}}" novalidate>
    <a href="{{route('login')}}" class="btn btn-sm mb-2 p-0 text-dark font-weight-bold">
        <i class="fas fa-arrow-left mr-2"></i>Kembali
    </a>
    <div class="card border-left-danger text-dark mb-3">
        <div class="card-body">Silahkan masukkan password baru untuk akun anda.</div>
    </div>
    <input type="hidden" name="email" value="{{$email}}" required>
    <input type="hidden" name="token" value="{{$token}}" required>
    <div class="form-group">
        <label>Password</label>
        <div class="input-group">
            <input type="password" class="form-control form-control-user" name="password" 
            placeholder="Password" required>
            <div class="input-group-append border-0">
                <span class="input-group-text bg-transparent custom-addon show-password-form">
                    <i class="fas fa-eye mr-1" name="show-password-icon"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>Konfirmasi Password</label>
        <div class="input-group mb-1">
            <input type="password" class="form-control form-control-user" name="password_confirmation" 
            placeholder="Konfirmasi Password" required>
            <div class="input-group-append border-0">
                <span class="input-group-text bg-transparent custom-addon show-password-form">
                    <i class="fas fa-eye mr-1" name="show-password-icon"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="mt-5 mb-3">
        <button type="submit" name="submit" class="btn btn-danger btn-user btn-block text-uppercase font-weight-bold">
            Reset Password<i class="fas fa-arrow-rotate-right ml-1"></i>
        </button>
    </div>
</form>
@endsection