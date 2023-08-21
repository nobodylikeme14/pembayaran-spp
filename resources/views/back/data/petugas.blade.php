@extends('layouts.back.master')

@section('page_name','Data Petugas')

@section('page_script')
<script src="{{asset('assets/js/back/petugas-script.js')}}"></script>
@endsection

@section('content')
<ul class="nav nav-tabs d-none" id="dataTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" data-toggle="tab" data-target="#data-tab" 
        role="tab" aria-selected="true">Data</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-toggle="tab" data-target="#form-tab" 
        role="tab" aria-selected="false">Form</a>
    </li>
</ul>
<div class="tab-content" id="dataTabContent">
    <div class="tab-pane fade show active" id="data-tab" role="tabpanel">
        <div class="my-3">
            <button name="add-data" class="btn btn-danger" data-action="{{route('petugas_tambah')}}">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-data w-100" data-url="{{route('petugas_data')}}" 
                    data-url-detail="{{route('petugas_detail')}}" data-url-edit="{{route('petugas_edit')}}" 
                    data-url-hapus="{{route('petugas_hapus')}}">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Username</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="form-tab" role="tabpanel">
        <div class="row">
            <div class="col-lg-6 col-12">
                <form name="form-data" class="needs-validation" novalidate>
                    <input type="hidden" name="id">
                    <div class="h5 form-title text-danger mb-3"></div>
                    <button type="button" class="btn btn-sm mb-2 pl-0 text-gray-800" name="button-back">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </button>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" placeholder="Masukkan Email" required>
                    </div>
                    <div class="form-group">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required>
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
                        <small class="form-text text-muted font-weight-bold password-helper d-none">
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
                        <small class="form-text text-muted font-weight-bold password-helper d-none">
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
    </div>
</div>
@endsection