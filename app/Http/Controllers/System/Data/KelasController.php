<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Kelas;
use DataTables;
use Session;
use Storage;

class KelasController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController)
    {
        $this->pusherController = $pusherController;
    }

    //Page
    public function kelas() {
        return view('back.data.kelas');
    }

    //Data
    public function kelas_data() {
        $data = Kelas::all();
        return DataTables::of($data)->make(true);
    }

    //Tambah
    public function kelas_tambah(Request $request) {
        $this->validate($request, [
            'kelas'                 => 'required|max:20|unique:kelas,kode_kelas',
            'kompetensi_keahlian'   => 'required'
        ],[
            'kelas.unique' => 'Data kelas '.$request->kelas.' sudah ada'
        ]);
        $data = new Kelas;
        $data->kode_kelas = strtoupper($request->kelas);
        $data->kompetensi_keahlian = $request->kompetensi_keahlian;
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data kelas berhasil disimpan'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Edit
    public function kelas_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:kelas',
        ], [
            'id.required' => 'Mohon sertakan id kelas',
            'id.exists' => 'Id kelas tidak ditemukan'
        ]);
        $data = Kelas::where('id', $request->id)->get();
        $responses = [
            'status' => 'success', 
            'data' => $data
        ];
        return Response()->json($responses);
    }
    public function kelas_edit(Request $request) {
        $this->validate($request, [
            'kelas'                 => 'required|max:20|unique:kelas,kode_kelas,'.$request->id.',id',
            'kompetensi_keahlian'   => 'required'
        ],[
            'kelas.unique' => 'Data kelas '.$request->kelas.' sudah ada'
        ]);
        $data = Kelas::find($request->id);
        $data->kode_kelas = strtoupper($request->kelas);
        $data->kompetensi_keahlian = $request->kompetensi_keahlian;
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data kelas berhasil diperbarui'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Hapus
    public function kelas_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:kelas',
        ], [
            'id.required' => 'Mohon sertakan id kelas',
            'id.exists' => 'Id kelas tidak ditemukan'
        ]);
        $kodeKelas = Kelas::where('id', $request->id)->value('kode_kelas');
        Kelas::where('id', $request->id)->delete();
        $dataDir = 'storage/export/'. $kodeKelas .'/';
        if (Storage::exists($dataDir)) {
            Storage::deleteDirectory($dataDir);
        }
        $responses = [
            'status' => 'success', 
            'message' => 'Data kelas berhasil dihapus'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses);
    }

    //Hapus semua
    public function kelas_hapus_all () {
        if(Kelas::count() > 0){
            Kelas::query()->delete();
            $dataDir = 'storage/export';
            if (Storage::exists($dataDir)) {
                Storage::deleteDirectory($dataDir);
            }
            $responses = [
                'status' => 'success', 
                'message' => 'Semua data kelas berhasil dihapus'
            ];
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json($responses);
        } else {
            return Response()->json([
                'message' => 'Tidak ada data kelas untuk dihapus'
            ], 404);
        }
    }
}
