@extends('layouts.back.master')

@section('page_name','Data Kelas')

@section('page_script')
<script src="{{asset('assets/js/back/kelas-script.js')}}"></script>
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
            <button name="add-data" class="btn btn-danger" data-action="{{route('kelas_tambah')}}">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
            <button name="delete-all-data" class="btn btn-danger mb-3 mb-md-0"
            data-url="{{route('kelas_hapus_all')}}">
                <i class="fas fa-trash mr-2"></i>Hapus Semua
            </button>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-data w-100" data-url="{{route('kelas_data')}}" 
                    data-url-detail="{{route('kelas_detail')}}" data-url-edit="{{route('kelas_edit')}}" 
                    data-url-hapus="{{route('kelas_hapus')}}">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
                                <th>Kompetensi Keahlian</th>
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
                        <label>Nama Kelas</label>
                        <input type="text" class="form-control" name="kelas" placeholder="Masukkan Nama Kelas" required>
                        <small class="form-text text-muted font-weight-bold">
                            Contoh : X-TKJ-1, XI-IPS-2, XII-RPL-3
                        </small>
                    </div>
                    <div class="form-group">
                        <label>Kompetensi Keahlian</label>
                        <select class="custom-select" name="kompetensi_keahlian" required>
                            <option disabled selected value>Pilih Kompetensi Keahlian</option>
                            <option>Akutansi dan Keuangan Lembaga</option>
                            <option>Desain Komunikasi Visual</option>
                            <option>Otomatisasi dan Tata Kelola Perkantoran</option>
                            <option>Rekayasa Perangkat Lunak</option>
                            <option>Tehnik dan Bisnis Sepeda Motor</option>
                            <option>Tehnik Kendaraan Ringan dan Otomotif</option>
                            <option>Tehnik Komputer dan Jaringan</option>
                            <option>Tehnik Pemesinan</option>
                        </select>
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