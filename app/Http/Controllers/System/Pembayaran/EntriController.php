<?php

namespace App\Http\Controllers\System\Pembayaran;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Spp;
use DataTables;
use Auth;

class EntriController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController)
    {
        $this->pusherController = $pusherController;
    }

    //Page
    public function entri_pembayaran() {
        $dataSpp = Spp::select('id', 'kode_spp', 'nominal')->orderBy('kode_spp')->get();
        $dataSiswa = Siswa::select('id', 'nisn', 'nama', 'kode_kelas')
        ->orderBy('kode_kelas', 'asc')
        ->orderBy('nama', 'asc')
        ->get();
        return view('back.pembayaran.entri', [
            'dataSpp' => $dataSpp,
            'dataSiswa' => $dataSiswa
        ]);
    }

    //Data
    public function entri_pembayaran_data() {
        $data = Pembayaran::join('siswa', 'siswa.id', 'pembayaran.id_siswa')
        ->join('spp', 'spp.id', 'pembayaran.id_spp')
        ->join('petugas', 'petugas.id', '=', 'pembayaran.id_petugas')
        ->select('pembayaran.id', 'pembayaran.tanggal_bayar', 'pembayaran.bulan_dibayar',
            'siswa.nama AS nama_siswa','siswa.kode_kelas AS kelas_siswa', 
            'spp.kode_spp AS spp_dibayar', 'petugas.nama AS nama_petugas')
        ->orderBy('pembayaran.created_at', 'desc')
        ->get();
        return DataTables::of($data)->make(true);
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
        if (Spp::where('id',$request->spp)->value('nominal') != $request->jumlah_bayar) {
            return response()->json(['errors' => [
                'jumlah_bayar' => ['Maaf, nominal jumlah bayar yang dimasukkan tidak sesuai.']
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
        $responses = [
            'status' => 'success', 
            'message' => 'Entri pembayaran berhasil disimpan'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Edit
    public function entri_pembayaran_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:pembayaran',
        ], [
            'id.required' => 'Mohon sertakan id pembayaran',
            'id.exists' => 'Id pembayaran tidak ditemukan'
        ]);
        $data = Pembayaran::where('id', $request->id)->get();
        $responses = [
            'status' => 'success', 
            'data' => $data
        ];
        return Response()->json($responses);
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
        if (Spp::where('id',$request->spp)->value('nominal') != $request->jumlah_bayar) {
            return response()->json(['errors' => [
                'jumlah_bayar' => ['Maaf, nominal jumlah bayar yang dimasukkan tidak sesuai.']
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
        $responses = [
            'status' => 'success', 
            'message' => 'Entri pembayaran berhasil diperbarui'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }
    
    //Hapus
    public function entri_pembayaran_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:pembayaran',
        ], [
            'id.required' => 'Mohon sertakan id pembayaran',
            'id.exists' => 'Id pembayaran tidak ditemukan'
        ]);
        Pembayaran::where('id', $request->id)->delete();
        $responses = [
            'status' => 'success', 
            'message' => 'Entri pembayaran berhasil dihapus'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Hapus all
    public function entri_pembayaran_hapus_all() {
        if(Pembayaran::count() > 0){
            Pembayaran::query()->delete();
            $responses = [
                'status' => 'success', 
                'message' => 'Semua entri pembayaran berhasil dihapus'
            ];
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json($responses);
        } else {
            return Response()->json([
                'message' => 'Tidak ada entri pembayaran untuk dihapus'
            ], 404);
        }
    }
}
