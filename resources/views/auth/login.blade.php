@extends('layouts.auth.master')

@section('page_name','Login')

@section('body_class', 'bg-auth-image')

@section('page_script')
<script src="{{asset('assets/js/auth/login-script.js')}}"></script>
@endsection

@section('content')
<form class="needs-validation user" name="form-login" action="{{route('loginPost')}}" novalidate>
    <div class="form-group">
        <label>Email atau Username</label>
        <input type="text" class="form-control form-control-user" name="username" 
        placeholder="Email atau Username" autofocus required>
    </div>
    <div class="form-group">
        <label>Password</label>
        <div class="input-group">
            <input type="password" class="form-control form-control-user" name="password" 
            placeholder="Password" required>
            <div class="input-group-append border-0">
                <span class="input-group-text bg-transparent custom-addon show-password-form">
                    <i class="fas fa-eye fa-fw mr-1" name="show-password-icon"></i>
                </span>
            </div>
        </div>
    </div>
    <div class="small text-right mt-3 mb-4 font-weight-bold">
        <a class="text-danger" href="{{route('lupa_password')}}">Lupa Password ?</a>
    </div>
    <div class="mt-5 mb-3">
        <button type="submit" name="submit" class="btn btn-danger btn-user btn-block text-uppercase font-weight-bold">
            Login<i class="fas fa-right-to-bracket ml-1"></i>
        </button>
    </div>
</form>
@endsection