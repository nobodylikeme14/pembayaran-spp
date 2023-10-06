@extends('layouts.back.master')

@section('page_name','Info Akun')

@section('page_script')
<script src="{{asset('assets/js/back/pages/akun-script.js')}}"></script>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-6 col-12">
        <form name="form-data" action="{{route('akun')}}" class="needs-validation" novalidate>
            <a href="{{route('dashboard')}}" class="btn btn-sm mb-2 pl-0 text-gray-800">
                <i class="fas fa-arrow-left mr-1"></i>Kembali
            </a>
            <div class="form-group">
                <label>Nama Akun</label>
                <input type="text" class="form-control" name="nama" value="{{Auth::user()->nama}}" placeholder="Masukkan Nama" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" class="form-control" name="email" value="{{Auth::user()->email}}" placeholder="Masukkan Email" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" class="form-control" name="username" value="{{Auth::user()->username}}" placeholder="Masukkan Username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-group">
                <input type="password" class="form-control" name="password" placeholder="Masukkan Password">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white show-password-form">
                            <i class="fas fa-eye" name="show-password-icon"></i>
                        </span>
                    </div>
                </div>
                <small class="form-text text-muted font-weight-bold password-helper">
                    Silahkan diisi jika ingin merubah password
                </small>
            </div>
            <div class="form-group">
                <label>Konfirmasi Password</label>
                <div class="input-group">
                <input type="password" class="form-control" name="password_confirmation" placeholder="Masukkan Konfirmasi Password">
                    <div class="input-group-append">
                        <span class="input-group-text bg-white show-password-form">
                            <i class="fas fa-eye" name="show-password-icon"></i>
                        </span>
                    </div>
                </div>
                <small class="form-text text-muted font-weight-bold password-helper">
                    Silahkan diisi jika ingin merubah password
                </small>
            </div>
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-danger">
                    Simpan<i class="fas fa-floppy-disk ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection