@extends('layouts.back.master')

@section('page_name','Data Siswa')

@section('page_script')
<script src="{{asset('assets/js/back/siswa-script.js')}}"></script>
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
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-toggle="tab" data-target="#export-tab" 
        role="tab" aria-selected="false">Export</a>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" data-toggle="tab" data-target="#import-tab" 
        role="tab" aria-selected="false">Import</a>
    </li>
</ul>
<div class="tab-content" id="dataTabContent">
    <div class="tab-pane fade show active" id="data-tab" role="tabpanel">
        <div class="d-flex flex-wrap justify-content-between align-items-center my-3">
            <button name="add-data" class="btn btn-danger mb-3 mb-md-0" data-action="{{route('siswa_tambah')}}">
                <i class="fas fa-plus mr-2"></i>Tambah
            </button>
            <div>
                <button name="export-data" class="btn btn-danger mb-3 mb-md-0">
                    <i class="fas fa-file-export mr-2"></i>Export
                </button>
                <button name="import-data" class="btn btn-danger mb-3 mb-md-0">
                    <i class="fas fa-file-import mr-2"></i>Import
                </button>
                <button name="delete-all-data" class="btn btn-danger mb-3 mb-md-0"
                data-url="{{route('siswa_hapus_all')}}">
                    <i class="fas fa-trash mr-2"></i>Hapus Semua
                </button>
            </div>
        </div>
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-data w-100" data-url="{{route('siswa_data')}}" 
                    data-url-detail="{{route('siswa_detail')}}" data-url-edit="{{route('siswa_edit')}}" 
                    data-url-hapus="{{route('siswa_hapus')}}">
                        <thead class="bg-danger text-white">
                            <tr>
                                <th>#</th>
                                <th>NISN</th>
                                <th>Nama</th>
                                <th>Kelas</th>
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
                        <label>NISN</label>
                        <input type="number" class="form-control" name="nisn" placeholder="Masukkan NISN" required>
                    </div>
                    <div class="form-group">
                        <label>NIS</label>
                        <input type="number" class="form-control" name="nis" placeholder="Masukkan NIS" required>
                    </div>
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Masukkan Nama" required>
                    </div>
                    <div class="form-group">
                        <label>Kelas</label>
                        <div class="form-group">
                            <select class="selectpicker form-control" name="kelas" data-live-search="true" required>
                                <option disabled selected value>Pilih Kelas</option>
                                @foreach($datakelas as $dk)
                                <option>{{$dk->kode_kelas}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Nomor HP</label>
                        <input type="number" class="form-control" name="nomor_hp" placeholder="Masukkan Nomor Hp" required>
                    </div>
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea class="form-control" name="alamat" rows="4" placeholder="Masukkan Alamat" required></textarea>
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
    <div class="tab-pane fade" id="export-tab" role="tabpanel">
        <div class="row">
            <div class="col-lg-6 col-12">
                <form name="form-export" action="{{route('siswa_export')}}" class="needs-validation" novalidate>
                    <div class="h5 form-title text-danger mb-3">Export Data Akun Siswa</div>
                    <button type="button" class="btn btn-sm mb-2 pl-0 text-gray-800" name="button-back">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </button>
                    <div class="form-group">
                        <label>Kelas</label>
                        <div class="form-group">
                            <select class="selectpicker form-control show-menu-arrow" name="kelas" data-live-search="true" required>
                                <option disabled selected value>Pilih Kelas</option>
                                @foreach($datakelas as $dk)
                                <option>{{$dk->kode_kelas}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-danger">
                            Export<i class="fas fa-file-export ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="import-tab" role="tabpanel">
        <div class="row">
            <div class="col-lg-6 col-12">
                <form name="form-import" action="{{route('siswa_import')}}" class="needs-validation" novalidate>
                    <div class="h5 form-title text-danger mb-3">Import Data Siswa</div>
                    <button type="button" class="btn btn-sm mb-2 pl-0 text-gray-800" name="button-back">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </button>
                    <ol class="pl-3">
                        <li>Download terlebih dahulu template Excel dibawah ini.</li>
                        <a href="{{asset('storage/template/EduCashLog-DataSiswa-Template.xlsx')}}" class="btn btn-danger my-2">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                        <li>Isi data pada file template sesuai dengan judul kolom.</li>
                        <li>Upload kembali file template yang sudah diisi data.</li>
                        <div class="custom-file my-2">
                            <input type="file" class="custom-file-input" name="file_import" required>
                            <label class="custom-file-label">Pilih File</label>
                        </div>
                        <li>Klik tombol <strong>Import</strong>.</li>
                    </ol>
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-danger">
                            Import<i class="fas fa-file-import ml-2"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection