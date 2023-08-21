<?php

namespace App\Http\Controllers\System\Pembayaran;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Petugas;
use App\Models\Siswa;
use DataTables;
use Auth;

class HistoriController extends Controller
{
    //Histori
    public function histori_pembayaran(){
        return view('back.pembayaran.histori');
    }

    public function histori_pembayaran_data(Request $request) {
        if (Auth::user()->privilege=="Administrator" || Auth::user()->privilege=="Petugas") {
            $data = Pembayaran::join('siswa', 'siswa.id', 'pembayaran.id_siswa')
            ->join('spp', 'spp.id', 'pembayaran.id_spp')
            ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
            ->select('pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
                'siswa.nama AS nama_siswa','siswa.kode_kelas AS kelas_siswa', 
                'spp.kode_spp AS spp_dibayar', 'petugas.nama AS nama_petugas')
            ->orderBy('pembayaran.created_at', 'desc')
            ->get();
            return DataTables::of($data)->make(true);
        } else {
            $dataSiswa = Siswa::select('nama', 'kode_kelas AS kelas', 'nisn', 'nis')
            ->where('id', Auth::user()->id)->first();
            $dataHistori = Pembayaran::join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
            ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
            ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
            ->select(
                'pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
                'pembayaran.jumlah_bayar', 'pembayaran.created_at', 
                'spp.kode_spp', 'petugas.nama AS nama_petugas')
            ->where('siswa.id', Auth::user()->id)
            ->orderBy('pembayaran.created_at', 'desc')
            ->get();
            return Response()->json([
                'dataSiswa' => $dataSiswa,
                'dataHistori' => $dataHistori
            ]);
        }
    }
    
    public function histori_pembayaran_search(Request $request) {
        $searchTerm = $request->input('search');
        $query = Pembayaran::join('siswa', 'siswa.id', '=', 'pembayaran.id_siswa')
            ->join('spp', 'spp.id', '=', 'pembayaran.id_spp')
            ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
            ->select(
                'pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
                'pembayaran.jumlah_bayar', 'pembayaran.created_at', 
                'siswa.nama AS nama_siswa', 'siswa.kode_kelas AS kelas_siswa', 
                'spp.kode_spp', 'petugas.nama AS nama_petugas')
            ->where('siswa.id', Auth::user()->id)
            ->orderBy('pembayaran.created_at', 'desc');
        if ($searchTerm) {
            $query->where(function($query) use ($searchTerm) {
                $query->orWhere('spp.kode_spp', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('petugas.nama', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('pembayaran.bulan_dibayar', 'LIKE', '%' . $searchTerm . '%')
                ->orWhere('pembayaran.tanggal_bayar', 'LIKE', '%' . $searchTerm . '%');
            });
        }
        $searchResults = $query->get();
        return response()->json($searchResults);
    }
}
