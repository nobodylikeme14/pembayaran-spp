<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Spp;
use DataTables;
use Throwable;

class SppController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController) {
        $this->pusherController = $pusherController;
    }

    //Page
    public function spp(Request $request) {
        if ($request->isMethod('post')) {
            try {
                $data = Spp::all()->map(function($item) {
                    return [
                        'id' => $item->id,
                        'spp' => "SPP ".substr($item->kode_spp, -4),
                        'nominal' => 'Rp ' . number_format($item->nominal, 0, ',', '.')
                    ];
                });
                return DataTables::of($data)->make(true);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan data SPP'
                ], 500);
            }
        }
        return view('back.data.spp');
    }

    //Tambah
    public function spp_tambah(Request $request) {
        $this->validate($request,[
            'tahun'     => 'required|unique:spp,tahun',
            'nominal'   => 'required|max:11',
        ],[
            'tahun.unique' => 'Data SPP Tahun '.$request->tahun.' sudah ada'
        ]);
        try {
            $data = new Spp;
            $data->kode_spp = "SPP-".$request->tahun;
            $data->tahun = $request->tahun;
            $data->nominal = $request->nominal;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data SPP berhasil ditambah'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data SPP'
            ], 500);
        }
    }

    //Edit
    public function spp_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:spp',
        ], [
            'id.required' => 'Mohon sertakan id SPP',
            'id.exists' => 'Id SPP tidak ditemukan'
        ]);
        try {
            $data = Spp::where('id', $request->id)->get();
            return Response()->json(['data' => $data]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data SPP'
            ], 500);
        }
    }
    public function spp_edit(Request $request) {
        $this->validate($request,[
            'tahun'     => 'required|unique:spp,tahun,'.$request->id.',id',
            'nominal'   => 'required|max:11',
        ],[
            'tahun.unique' => 'Data SPP Tahun '.$request->tahun.' sudah ada'
        ]);
        try {
            $data = Spp::find($request->id);
            $data->kode_spp = "SPP-".$request->tahun;
            $data->tahun = $request->tahun;
            $data->nominal = $request->nominal;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data SPP berhasil diperbarui'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data SPP'
            ], 500);
        }
    }
    
    //Hapus
    public function spp_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:spp',
        ], [
            'id.required' => 'Mohon sertakan id SPP',
            'id.exists' => 'Id SPP tidak ditemukan'
        ]);
        try {
            Spp::where('id', $request->id)->delete();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data SPP berhasil dihapus'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data SPP'
            ], 500);
        }
    }

    //Hapus semua
    public function spp_hapus_all () {
        if(Spp::count() > 0){
            try {
                Schema::disableForeignKeyConstraints();
                Spp::truncate();
                Schema::enableForeignKeyConstraints();
                if ($this->pusherController->isInternetConnected()) {
                    $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
                }
                return Response()->json([
                    'message' => 'Semua data SPP berhasil dihapus'
                ]);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat menghapus data SPP'
                ], 500);
            }
        } else {
            return Response()->json([
                'message' => 'Tidak ada data SPP untuk dihapus'
            ], 404);
        }
    }
}
