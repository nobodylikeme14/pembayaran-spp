<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Petugas;
use App\Models\Kelas;
use App\Models\Spp;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Throwable;

class DashboardController extends Controller
{
    public function dashboard(Request $request){
        if ($request->isMethod('post')) {
            try {
                $dataSiswa = Siswa::count();
                $dataPetugas = Petugas::where('privilege', '!=', "Administrator")->count();
                $dataKelas = Kelas::count();
                $dataSpp = Spp::count();
                $dataEntri = Pembayaran::count();
                $dataTransaksi = Pembayaran::with(['siswa', 'spp', 'petugas'])->orderBy('created_at', 'desc')
                ->get()->map(function($item) {
                    $tanggalBayar = Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y');
                    $sppDibayar = "SPP ".$item->bulan_dibayar. " ".substr($item->spp->kode_spp, -4);
                    $jumlahBayar = 'Rp ' . number_format($item->jumlah_bayar, 0, ',', '.');
                    return [
                        'nama_petugas' => $item->petugas->nama,
                        'nama_siswa' => $item->siswa->nama,
                        'kelas_siswa' => $item->siswa->kode_kelas,
                        'tanggal_bayar' => $tanggalBayar,
                        'spp_dibayar' => $sppDibayar,
                        'jumlah_bayar' => $jumlahBayar,
                        'created_at' => $item->created_at
                    ];
                });
                return Response()->json([
                    'dataSiswa' => $dataSiswa,
                    'dataPetugas' => $dataPetugas,
                    'dataKelas' => $dataKelas,
                    'dataSpp' => $dataSpp,
                    'dataEntri' => $dataEntri,
                    'dataHistori' => $dataEntri,
                    'dataTransaksi' => $dataTransaksi
                ]);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan data dashboard'
                ], 500);
            }
        }
        return view('back.dashboard');
    }

    public function dashboard_search(Request $request) {
        try {
            $searchTerm = $request->input('search');
            $data = Pembayaran::with(['siswa' ,'spp', 'petugas'])->orderBy('created_at', 'desc')
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->orWhereHas('siswa', function ($query) use ($searchTerm) {
                        $query->where('nama', 'LIKE', '%' . $searchTerm . '%')
                        ->orWhere('kode_kelas', 'LIKE', '%' . $searchTerm . '%');
                    })->orWhereHas('spp', function ($query) use ($searchTerm) {
                        $query->where('kode_spp', 'LIKE', '%' . $searchTerm . '%');
                    })->orWhereHas('petugas', function ($query) use ($searchTerm) {
                        $query->where('nama', 'LIKE', '%' . $searchTerm . '%');
                    })->orWhere('tanggal_bayar', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('bulan_dibayar', 'LIKE', '%' . $searchTerm . '%');
                });
            })->get()->map(function ($item) {
                $tanggalBayar = Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y');
                $sppDibayar = "SPP ".$item->bulan_dibayar. " ".substr($item->spp->kode_spp, -4);
                $jumlahBayar = 'Rp ' . number_format($item->jumlah_bayar, 0, ',', '.');
                return [
                    'nama_siswa' => $item->siswa->nama,
                    'kelas_siswa' => $item->siswa->kode_kelas,
                    'tanggal_bayar' => $tanggalBayar,
                    'jumlah_bayar' => $jumlahBayar,
                    'nama_petugas' => $item->petugas->nama,
                    'spp_dibayar' => $sppDibayar,
                    'created_at' => $item->created_at
                ];
            });
            return response()->json($data);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan daftar transaksi'
            ], 500);
        }
    }    
}
