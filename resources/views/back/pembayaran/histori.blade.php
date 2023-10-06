@extends('layouts.back.master')

@section('page_name', 'Histori Pembayaran')

@section('page_script')
<script src="{{asset('assets/js/back/pages/histori-script.js')}}"></script>
@endsection

@section('content')
@if(Auth::user()->privilege == "Siswa")
<div class="row data-siswa-container">
    <div class="col-lg-3 col-12 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Nama Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{Auth::user()->nama}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Kelas Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{Auth::user()->kode_kelas}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            NISN Siswa
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{Auth::user()->nisn}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-12 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            NIS Siswa
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    {{Auth::user()->nis}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Daftar Transaksi</h1>
            <div class="form-inline my-2 my-lg-0 mr-0">
                <span class="mr-2">Cari Histori :</span>
                <input class="form-control" autocomplete="off" name="search" type="text"
                data-url="{{route('histori_pembayaran_search')}}">
            </div>
        </div>
        <div class="loading-animation mt-2 mb-4">
            <div class="text-center my-2">
                <i class="fas fa-fan fa-spin fa-4x mt-5 text-danger"></i>
            </div>
        </div>
        <div class="row histori-transaksi-container"></div>
        <div class="not-found-img">
            <div class="text-center my-3">
                <img src="{{asset('assets/img/not-found-img.svg')}}" width="200px" alt="No Data Image">
                <div class="mt-3">
                    <h3>Tidak ada transaksi yang ditemukan</h3>
                </div>
            </div>
        </div>
    </div>
</div>
@else
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-data w-100">
                <thead class="bg-danger text-white">
                    <tr>
                        <th>#</th>
                        <th>Petugas</th>
                        <th>Siswa</th>
                        <th>Tanggal Bayar</th>
                        <th>SPP Dibayar</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>
@endif
@endsection