@extends('layouts.back.master')

@section('page_name','Laporan')

@section('page_script')
<script src="{{asset('assets/js/back/laporan-script.js')}}"></script>
@endsection

@section('content')
<div class="mb-0">
    <h1 class="display-5 text-gray-800">Halo, {{ Auth::user()->nama }}</h1>
    <p class="lead">Dihalaman ini, anda dapat mencetak laporan pembayaran berupa file yang berekstensi pdf.</p>
</div>
<div class="row mb-4">
    <div class="col-lg-6 col-12">
        <form name="form-laporan" class="needs-validation" novalidate>
            <div class="form-group">
                <label>SPP</label>
                <div class="form-group">
                    <select class="selectpicker form-control" name="spp" data-live-search="true" required>
                        <option disabled selected value>Pilih SPP</option>
                        @foreach($dataSpp as $ds)
                        <option value="{{$ds->kode_spp}}">
                            {{ str_replace('-', ' ', $ds->kode_spp) }} (Rp {{ number_format($ds->nominal, 0, ',', '.') }})
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label>Bulan SPP</label>
                <select class="custom-select" name="bulan_spp" required>
                    <option disabled selected value>Pilih Bulan SPP</option>
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
                <label>Kelas</label>
                <div class="form-group">
                    <select class="selectpicker form-control" name="kelas" data-live-search="true" required>
                        <option disabled selected value>Pilih Kelas</option>
                        @foreach($dataKelas as $dk)
                        <option>{{$dk->kode_kelas}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group mt-4">
                <button type="submit" class="btn btn-danger">
                    Cetak<i class="fas fa-print ml-2"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection