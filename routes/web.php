<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\System\LaporanController;
use App\Http\Controllers\System\AkunController;
use App\Http\Controllers\System\DashboardController;
use App\Http\Controllers\System\Data\SppController;
use App\Http\Controllers\System\Data\KelasController;
use App\Http\Controllers\System\Data\SiswaController;
use App\Http\Controllers\System\Data\PetugasController;
use App\Http\Controllers\System\Pembayaran\EntriController;
use App\Http\Controllers\System\Pembayaran\HistoriController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*==> MAIN ROUTE <==*/
Route::middleware('guest')->group(function () {
    //Login
    Route::get('/', [AuthController::class, 'login'])->name('login');
    Route::post('/', [AuthController::class, 'loginPost'])->name('loginPost');

    Route::get('/test-mail', function() {
        return view('password.email');
    });

    //Reset Password
    Route::get('/lupa-password', [ResetPasswordController::class, 'lupa_password'])->name('lupa_password');
    Route::post('/lupa-password', [ResetPasswordController::class, 'lupa_passwordPost'])->name('lupa_passwordPost');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'reset_password'])->name('reset_password');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset_passwordPost'])->name('reset_passwordPost');
});

/*==> ROUTE PRIVILEGE:ADMINISTRATOR <==*/
Route::group(['middleware' => ['auth:petugas','PrivilegeCheck:Administrator']], function (){
    //Dashboard
    Route::prefix('/dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
        Route::post('/', [DashboardController::class, 'dashboard_data'])->name('dashboard_data');
        Route::post('/search', [DashboardController::class, 'dashboard_search'])->name('dashboard_search');
    });
    //Data SPP
    Route::prefix('/data-spp')->group(function () {
        Route::get('/', [SppController::class, 'spp'])->name('spp');
        Route::post('/', [SppController::class, 'spp_data'])->name('spp_data');
        Route::post('/tambah', [SppController::class, 'spp_tambah'])->name('spp_tambah');
        Route::post('/detail', [SppController::class, 'spp_detail'])->name('spp_detail');
        Route::post('/edit', [SppController::class, 'spp_edit'])->name('spp_edit');
        Route::post('/hapus', [SppController::class, 'spp_hapus'])->name('spp_hapus');
        Route::post('/hapus-all', [SppController::class, 'spp_hapus_all'])->name('spp_hapus_all');
    });
    //Data Kelas
    Route::prefix('/data-kelas')->group(function () {
        Route::get('/', [KelasController::class, 'kelas'])->name('kelas');
        Route::post('/', [KelasController::class, 'kelas_data'])->name('kelas_data');
        Route::post('/tambah', [KelasController::class, 'kelas_tambah'])->name('kelas_tambah');
        Route::post('/detail', [KelasController::class, 'kelas_detail'])->name('kelas_detail');
        Route::post('/edit', [KelasController::class, 'kelas_edit'])->name('kelas_edit');
        Route::post('/hapus', [KelasController::class, 'kelas_hapus'])->name('kelas_hapus');
        Route::post('/hapus-all', [KelasController::class, 'kelas_hapus_all'])->name('kelas_hapus_all');
    });
    //Data Siswa
    Route::prefix('/data-siswa')->group(function () {
        Route::get('/', [SiswaController::class, 'siswa'])->name('siswa');
        Route::post('/', [SiswaController::class, 'siswa_data'])->name('siswa_data');
        Route::post('/tambah', [SiswaController::class, 'siswa_tambah'])->name('siswa_tambah');
        Route::post('/detail', [SiswaController::class, 'siswa_detail'])->name('siswa_detail');
        Route::post('/edit', [SiswaController::class, 'siswa_edit'])->name('siswa_edit');
        Route::post('/hapus', [SiswaController::class, 'siswa_hapus'])->name('siswa_hapus');
        Route::post('/export', [SiswaController::class, 'siswa_export'])->name('siswa_export');
        Route::post('/import', [SiswaController::class, 'siswa_import'])->name('siswa_import');
        Route::post('/hapus-all', [SiswaController::class, 'siswa_hapus_all'])->name('siswa_hapus_all');
    });
    //Data Petugas
    Route::prefix('/data-petugas')->group(function () {
        Route::get('/', [PetugasController::class, 'petugas'])->name('petugas');
        Route::post('/', [PetugasController::class, 'petugas_data'])->name('petugas_data');
        Route::post('/tambah', [PetugasController::class, 'petugas_tambah'])->name('petugas_tambah');
        Route::post('/detail', [PetugasController::class, 'petugas_detail'])->name('petugas_detail');
        Route::post('/edit', [PetugasController::class, 'petugas_edit'])->name('petugas_edit');
        Route::post('/hapus', [PetugasController::class, 'petugas_hapus'])->name('petugas_hapus');
    });
    //Entri Pembayaran
    Route::prefix('/entri-pembayaran')->group(function () {
        Route::post('/hapus-all', [EntriController::class, 'entri_pembayaran_hapus_all'])->name('entri_pembayaran_hapus_all');
    });
    //Laporan
    Route::prefix('/laporan')->group(function () {
        Route::get('/', [LaporanController::class, 'laporan'])->name('laporan');
        Route::post('/', [LaporanController::class, 'laporan_cetak'])->name('laporan_cetak');
    });
    //Info Akun
    Route::prefix('/akun')->group(function () {
        Route::get('/', [AkunController::class, 'akun'])->name('akun');
        Route::post('/', [AkunController::class, 'akun_simpan'])->name('akun_simpan');
    });
});

/*==> ROUTE PRIVILEGE:ADMINISTRATOR, PETUGAS <==*/
Route::group(['middleware' => ['auth:petugas','PrivilegeCheck:Administrator,Petugas']], function (){
    //Entri Pembayaran
    Route::prefix('/entri-pembayaran')->group(function () {
        Route::get('/', [EntriController::class, 'entri_pembayaran'])->name('entri_pembayaran');
        Route::post('/', [EntriController::class, 'entri_pembayaran_data'])->name('entri_pembayaran_data');
        Route::post('/tambah', [EntriController::class, 'entri_pembayaran_tambah'])->name('entri_pembayaran_tambah');
        Route::post('/detail', [EntriController::class, 'entri_pembayaran_detail'])->name('entri_pembayaran_detail');
        Route::post('/edit', [EntriController::class, 'entri_pembayaran_edit'])->name('entri_pembayaran_edit');
        Route::post('/hapus', [EntriController::class, 'entri_pembayaran_hapus'])->name('entri_pembayaran_hapus');
    });
});

/*==> ROUTE PRIVILEGE:ADMINISTRATOR, PETUGAS, SISWA <==*/
Route::group(['middleware' => ['auth:petugas,siswa','PrivilegeCheck:Administrator,Petugas,Siswa']], function (){
    Route::prefix('/histori-pembayaran')->group(function () {
        Route::get('/', [HistoriController::class, 'histori_pembayaran'])->name('histori_pembayaran');
        Route::post('/', [HistoriController::class, 'histori_pembayaran_data'])->name('histori_pembayaran_data');
        Route::post('/search', [HistoriController::class, 'histori_pembayaran_search'])->name('histori_pembayaran_search');
    });
});

//Logout
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');