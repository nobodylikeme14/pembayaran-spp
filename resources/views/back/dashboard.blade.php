@extends('layouts.back.master')

@section('page_name','Dashboard')

@section('page_script')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script src="{{asset('assets/js/back/dashboard-script.js')}}"></script>
@if(request()->has('message'))
<script>
    $(document).ready( function() {
        Swal.fire({
            icon: 'success',
            title: "{{ request()->input('message') }}"
        });
    });
</script>
@endif
@endsection

@section('content')
<div class="row data-dashboard-container" data-url="{{route('dashboard_data')}}">
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('spp')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Data SPP
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <span name="dataSpp" class="mr-1"></span>SPP
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-file-invoice-dollar fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('kelas')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Data Kelas
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <span name="dataKelas" class="mr-1"></span>Kelas
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-chalkboard fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('siswa')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Data Siswa
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <span name="dataSiswa" class="mr-1"></span>Siswa
                            </div> 
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('petugas')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Data Petugas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                <span name="dataPetugas" class="mr-1"></span>Petugas
                            </div>    
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-dark shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('entri_pembayaran')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                            Entri Pembayaran
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <span name="dataEntri" class="mr-1"></span>Entri
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-dark"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-12 mb-4">
        <div class="card border-left-danger shadow h-100 py-2">
            <div class="card-body">
                <a href="{{route('histori_pembayaran')}}" class="stretched-link"></a>
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                            Histori Pembayaran
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                    <span name="dataHistori" class="mr-1"></span>Histori
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-book fa-2x text-danger"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Histori Transaksi Terbaru</h1>
    <div class="form-inline my-2 my-lg-0 mr-0">
        <span class="mr-2">Cari Histori :</span>
        <input class="form-control" autocomplete="off" name="search" type="text"
        data-url="{{route('dashboard_search')}}">
    </div>
</div>
<div class="row histori-transaksi-container"></div>
<div class="lihat-lainnya mb-4 mt-3">
    <div class="text-center my-2">
        <a href="{{route('histori_pembayaran')}}" class="btn btn-danger">
            Lihat <span name="total-data-histori"></span> histori lainnya<i class="fas fa-arrow-right ml-2"></i>
        </a>
    </div>
</div>
<div class="loading-animation mt-2 mb-4">
    <div class="text-center my-2">
        <div class="spinner-grow text-danger" style="width: 4rem; height: 4rem;" role="status"></div>
    </div>
</div>
<div class="not-found-img">
    <div class="text-center my-3">
        <img src="{{asset('assets/img/undraw_not_found_60pq.svg')}}" width="200px" alt="No Data Image">
        <div class="mt-3">
            <h3>Tidak ada transaksi yang ditemukan</h3>
        </div>
    </div>
</div>
@endsection