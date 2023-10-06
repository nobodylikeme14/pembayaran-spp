<?php

namespace App\Http\Controllers\System\Pembayaran;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use Carbon\Carbon;
use DataTables;
use Throwable;
use Auth;

class HistoriController extends Controller
{
    //Histori
    public function histori_pembayaran(Request $request) {
        if ($request->isMethod('post')) {
            try {
                if (Auth::user()->privilege=="Administrator" || Auth::user()->privilege=="Petugas") {
                    $data = Pembayaran::with(['siswa', 'spp', 'petugas'])->orderBy('created_at', 'desc')
                    ->get()->map(function($item) {
                        $siswa = $item->siswa->nama. " (".$item->siswa->kode_kelas.")";
                        $tanggalBayar = Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y');
                        $sppDibayar = $item->bulan_dibayar. " ".substr($item->spp->kode_spp, -4);
                        return [
                            'nama_petugas' => $item->petugas->nama,
                            'siswa' => $siswa,
                            'tanggal_bayar' => $tanggalBayar,
                            'spp_dibayar' => $sppDibayar
                        ];
                    });
                    return DataTables::of($data)->make(true);
                } else {
                    $data = Pembayaran::with(['spp', 'petugas'])->orderBy('created_at', 'desc')
                    ->where('id_siswa', Auth::user()->id)->get()->map(function($item) {
                        $tanggalBayar = Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y');
                        $sppDibayar = "SPP ".$item->bulan_dibayar. " ".substr($item->spp->kode_spp, -4);
                        $jumlahBayar = 'Rp ' . number_format($item->jumlah_bayar, 0, ',', '.');
                        return [
                            'tanggal_bayar' => $tanggalBayar,
                            'jumlah_bayar' => $jumlahBayar,
                            'nama_petugas' => $item->petugas->nama,
                            'spp_dibayar' => $sppDibayar,
                            'created_at' => $item->created_at
                        ];
                    });
                    return Response()->json(['data' => $data]);
                }
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan histori pembayaran'
                ], 500);
            }   
        }
        return view('back.pembayaran.histori');
    }
    
    //Histori Search
    public function histori_pembayaran_search(Request $request) {
        try {
            $searchTerm = $request->input('search');
            $data = Pembayaran::with(['spp', 'petugas'])->orderBy('created_at', 'desc')
            ->when($searchTerm, function ($query) use ($searchTerm) {
                $query->where(function ($query) use ($searchTerm) {
                    $query->orWhereHas('spp', function ($query) use ($searchTerm) {
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
