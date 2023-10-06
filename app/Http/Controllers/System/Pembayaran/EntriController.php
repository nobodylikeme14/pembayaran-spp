<?php

namespace App\Http\Controllers\System\Pembayaran;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use Carbon\Carbon;
use DataTables;
use Throwable;
use Auth;

class EntriController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController) {
        $this->pusherController = $pusherController;
    }

    //Page
    public function entri_pembayaran(Request $request) {
        if ($request->isMethod('post')) {
            try {
                $data = Pembayaran::with(['siswa', 'spp', 'petugas'])->orderBy('created_at', 'desc')
                ->get()->map(function($item) {
                    $siswa = $item->siswa->nama. " (".$item->siswa->kode_kelas.")";
                    $tanggalBayar = Carbon::parse($item->tanggal_bayar)->locale('id')->isoFormat('DD MMMM Y');
                    $sppDibayar = $item->bulan_dibayar. " ".substr($item->spp->kode_spp, -4);
                    return [
                        'id' => $item->id,
                        'nama_petugas' => $item->petugas->nama,
                        'siswa' => $siswa,
                        'tanggal_bayar' => $tanggalBayar,
                        'spp_dibayar' => $sppDibayar,
                        
                    ];
                });
                return DataTables::of($data)->make(true);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan entri pembayaran'
                ], 500);
            }
        }
        $dataSpp = Spp::select('id', 'kode_spp', 'nominal')->orderBy('kode_spp')->get();
        $dataSiswa = Siswa::select('id', 'nisn', 'nama', 'kode_kelas')
        ->orderBy('kode_kelas', 'asc')->orderBy('nama', 'asc')->get();
        return view('back.pembayaran.entri', [
            'dataSpp' => $dataSpp,
            'dataSiswa' => $dataSiswa
        ]);
    }

    //Tambah
    public function entri_pembayaran_tambah(Request $request) {
        $this->validate($request, [
            'siswa'         => 'required|exists:siswa,id',
            'spp'           => 'required|exists:spp,id',
            'bulan_dibayar' => 'required',
            'jumlah_bayar'  => 'required|max:11',
            'tanggal_bayar' => 'required',
        ], [
            'siswa.exists'  => 'Siswa yang anda pilih tidak ditemukan',
            'spp.exists'    => 'SPP yang anda pilih tidak ditemukan'
        ]);
        try {
            if(Spp::where('id',$request->spp)->value('nominal') != $request->jumlah_bayar) {
                return response()->json(['errors' => [
                    'jumlah_bayar' => ['Nominal jumlah bayar yang dimasukkan tidak sesuai.']
                ]], 422);
            }
            $kode_pembayaran = $request->siswa . "-" . strtoupper(substr($request->bulan_dibayar, 0, 3)) . "-" .  $request->spp;
            $data = new Pembayaran;
            $data->kode_pembayaran = $kode_pembayaran;
            $data->id_siswa = $request->siswa;
            $data->id_petugas = Auth::guard('petugas')->user()->id;
            $data->tanggal_bayar = $request->tanggal_bayar;
            $data->bulan_dibayar = $request->bulan_dibayar;
            $data->id_spp = $request->spp;
            $data->jumlah_bayar = $request->jumlah_bayar;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Entri pembayaran berhasil ditambah'
            ]);
        } catch (\Exception $ex) {
            return Response()->json([
                'message' => $ex->getMessage()
            ], 500);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan entri pembayaran'
            ], 500);
        }
    }

    //Edit
    public function entri_pembayaran_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:pembayaran',
        ], [
            'id.required' => 'Mohon sertakan id pembayaran',
            'id.exists' => 'Id pembayaran tidak ditemukan'
        ]);
        try {
            $data = Pembayaran::where('id', $request->id)->get();
            return Response()->json(['data' => $data]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan entri pembayaran'
            ], 500);
        }
    }
    public function entri_pembayaran_edit(Request $request) {
        $this->validate($request, [
            'siswa'         => 'required|exists:siswa,id',
            'spp'           => 'required|exists:spp,id',
            'bulan_dibayar' => 'required',
            'jumlah_bayar'  => 'required|max:11',
            'tanggal_bayar' => 'required',
        ], [
            'siswa.exists'  => 'Siswa yang anda pilih tidak ditemukan',
            'spp.exists'    => 'SPP yang anda pilih tidak ditemukan'
        ]);
        try {
            if (Spp::where('id',$request->spp)->value('nominal') != $request->jumlah_bayar) {
                return response()->json(['errors' => [
                    'jumlah_bayar' => ['Nominal jumlah bayar yang dimasukkan tidak sesuai.']
                ]], 422);
            }
            $kode_pembayaran = $request->siswa . "-" . strtoupper(substr($request->bulan_dibayar, 0, 3)) . "-" .  $request->spp;
            $data = Pembayaran::find($request->id);
            $data->kode_pembayaran = $kode_pembayaran;
            $data->id_siswa = $request->siswa;
            $data->id_petugas = Auth::guard('petugas')->user()->id;
            $data->tanggal_bayar = $request->tanggal_bayar;
            $data->bulan_dibayar = $request->bulan_dibayar;
            $data->id_spp = $request->spp;
            $data->jumlah_bayar = $request->jumlah_bayar;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Entri pembayaran berhasil diperbarui'
            ]);
        } catch (\Exception $ex) {
            return Response()->json([
                'message' => $ex->getMessage()
            ], 500);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan entri pembayaran'
            ], 500);
        }
    }
    
    //Hapus
    public function entri_pembayaran_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:pembayaran',
        ], [
            'id.required' => 'Mohon sertakan id pembayaran',
            'id.exists' => 'Id pembayaran tidak ditemukan'
        ]);
        try {
            Pembayaran::where('id', $request->id)->delete();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Entri pembayaran berhasil dihapus'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menghapus entri pembayaran'
            ], 500);
        }
    }

    //Hapus all
    public function entri_pembayaran_hapus_all() {
        if(Pembayaran::count() > 0){
            try {
                Schema::disableForeignKeyConstraints();
                Pembayaran::truncate();
                Schema::enableForeignKeyConstraints();
                if ($this->pusherController->isInternetConnected()) {
                    $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
                }
                return Response()->json([
                    'message' => 'Semua entri pembayaran berhasil dihapus'
                ]);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat menghapus entri pembayaran'
                ], 500);
            }
        } else {
            return Response()->json([
                'message' => 'Tidak ada entri pembayaran untuk dihapus'
            ], 404);
        }
    }
}
