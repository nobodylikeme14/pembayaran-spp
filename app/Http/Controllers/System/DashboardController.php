<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Pembayaran;

class DashboardController extends Controller
{
    public function dashboard(){
        return view('back.dashboard');
    }

    public function dashboard_data() {
        $dataSiswa = Siswa::count();
        $dataPetugas = Petugas::where('privilege', '!=', "Administrator")->count();
        $dataKelas = Kelas::count();
        $dataSpp = Spp::count();
        $dataEntri = Pembayaran::count();
        $dataTransaksi = Pembayaran::join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
        ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
        ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
        ->select(
            'pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
            'pembayaran.jumlah_bayar', 'pembayaran.created_at', 
            'siswa.nama AS nama_siswa', 'siswa.kode_kelas AS kelas_siswa', 
            'spp.kode_spp', 'petugas.nama AS nama_petugas')
        ->orderBy('pembayaran.created_at', 'desc')
        ->get();
        return Response()->json([
            'dataSiswa' => $dataSiswa,
            'dataPetugas' => $dataPetugas,
            'dataKelas' => $dataKelas,
            'dataSpp' => $dataSpp,
            'dataEntri' => $dataEntri,
            'dataHistori' => $dataEntri,
            'dataTransaksi' => $dataTransaksi
        ]);
    }

    public function dashboard_search(Request $request) {
        $searchTerm = $request->input('search');
        $query = Pembayaran::join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
            ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
            ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
            ->select(
                'pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
                'pembayaran.jumlah_bayar', 'pembayaran.created_at', 
                'siswa.nama AS nama_siswa', 'siswa.kode_kelas AS kelas_siswa', 
                'spp.kode_spp', 'petugas.nama AS nama_petugas')
            ->orderBy('pembayaran.created_at', 'desc');
        if ($searchTerm) {
            $query->where(function($query) use ($searchTerm) {
                $query->orWhere('siswa.nama', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('siswa.kode_kelas', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('spp.kode_spp', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('petugas.nama', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('pembayaran.tanggal_bayar', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('pembayaran.bulan_dibayar', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $searchResults = $query->get();
        return response()->json($searchResults);
    }    
}
