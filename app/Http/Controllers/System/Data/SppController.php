<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Spp;
use DataTables;
use Session;

class SppController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController)
    {
        $this->pusherController = $pusherController;
    }

    //Page
    public function spp() {
        return view('back.data.spp');
    }

    //Data
    public function spp_data() {
        $data = Spp::all();
        return DataTables::of($data)->make(true);
    }

    //Tambah
    public function spp_tambah(Request $request) {
        $this->validate($request,[
            'tahun'     => 'required|unique:spp,tahun',
            'nominal'   => 'required|max:11',
        ],[
            'tahun.unique' => 'Data SPP Tahun '.$request->tahun.' sudah ada'
        ]);
        $data = new Spp;
        $data->kode_spp = "SPP-".$request->tahun;
        $data->tahun = $request->tahun;
        $data->nominal = $request->nominal;
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data SPP berhasil disimpan'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Edit
    public function spp_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:spp',
        ], [
            'id.required' => 'Mohon sertakan id SPP',
            'id.exists' => 'Id SPP tidak ditemukan'
        ]);
        $data = Spp::where('id', $request->id)->get();
        $responses = [
            'status' => 'success', 
            'data' => $data
        ];
        return Response()->json($responses);
    }
    public function spp_edit(Request $request) {
        $this->validate($request,[
            'tahun'     => 'required|unique:spp,tahun,'.$request->id.',id',
            'nominal'   => 'required|max:11',
        ],[
            'tahun.unique' => 'Data SPP Tahun '.$request->tahun.' sudah ada'
        ]);
        $data = Spp::find($request->id);
        $data->kode_spp = "SPP-".$request->tahun;
        $data->tahun = $request->tahun;
        $data->nominal = $request->nominal;
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data SPP berhasil diperbarui'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }
    
    //Hapus
    public function spp_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:spp',
        ], [
            'id.required' => 'Mohon sertakan id SPP',
            'id.exists' => 'Id SPP tidak ditemukan'
        ]);
        Spp::where('id', $request->id)->delete();
        $responses = [
            'status' => 'success', 
            'message' => 'Data SPP berhasil dihapus'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses);
    }

    //Hapus semua
    public function spp_hapus_all () {
        if(Spp::count() > 0){
            Spp::query()->delete();
            $responses = [
                'status' => 'success', 
                'message' => 'Semua data SPP berhasil dihapus'
            ];
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json($responses);
        } else {
            return Response()->json([
                'message' => 'Tidak ada data SPP untuk dihapus'
            ], 404);
        }
    }
}
