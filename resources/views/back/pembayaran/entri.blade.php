@extends('layouts.back.master')

@section('page_name','Entri Pembayaran')

@section('page_script')
<script src="{{asset('assets/js/back/entri-script.js')}}"></script>
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
        <div class="d-flex flex-wrap justify-content-between align-items-center my-3">
            <button name="add-data" class="btn btn-danger" data-action="{{route('entri_pembayaran_tambah')}}">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
            @if(Auth::user()->privilege == "Administrator")
            <button name="delete-all-data" class="btn btn-danger mb-3 mb-md-0"
            data-url="{{route('entri_pembayaran_hapus_all')}}">
                <i class="fas fa-trash mr-2"></i>Hapus Semua
            </button>
            @endif
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-data w-100" data-url="{{route('entri_pembayaran_data')}}" 
                    data-url-detail="{{route('entri_pembayaran_detail')}}" data-url-edit="{{route('entri_pembayaran_edit')}}" 
                    data-url-hapus="{{route('entri_pembayaran_hapus')}}">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>#</th>
                                <th>Petugas</th>
                                <th>Siswa</th>
                                <th>Tanggal Bayar</th>
                                <th>SPP Dibayar</th>
                                <th>Status</th>
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
                        <label>Siswa</label>
                        <div class="form-group">
                            <select class="selectpicker form-control" name="siswa" data-live-search="true" required>
                                <option disabled selected value>Pilih Siswa</option>
                                @foreach($dataSiswa as $dw)
                                <option value="{{$dw->id}}">
                                    {{$dw->nama}} ({{$dw->kode_kelas}}) ({{$dw->nisn}})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>SPP Dibayar</label>
                        <div class="form-group">
                            <select class="selectpicker form-control" name="spp" data-live-search="true" required>
                                <option disabled selected value>Pilih SPP Dibayar</option>
                                @foreach($dataSpp as $ds)
                                <option value="{{$ds->id}}">
                                    {{ str_replace('-', ' ', $ds->kode_spp) }} (Rp {{ number_format($ds->nominal, 0, ',', '.') }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Bulan Dibayar</label>
                        <select class="custom-select" name="bulan_dibayar" required>
                            <option disabled selected value>Pilih Bulan Dibayar</option>
                            <option>Januari</option>
                            <option>Februari</option>
                            <option>Maret</option>
                            <option>April</option>
                            <option>Mei</option>
                            <option>Juni</option>
                            <option>Juli</option>
                            <option>Agustus</option>
                            <option>September</option>
                            <option>Oktober</option>
                            <option>November</option>
                            <option>Desember</option>
                        </select>
                    </div>  
                    <div class="form-group">
                        <label>Jumlah Bayar</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-white">Rp</div>
                            </div>
                            <input type="number" class="form-control" name="jumlah_bayar" placeholder="Masukkan Jumlah Bayar" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Bayar</label>
                        <input type="date" class="form-control" name="tanggal_bayar" placeholder="Pilih Tanggal Bayar" required>
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