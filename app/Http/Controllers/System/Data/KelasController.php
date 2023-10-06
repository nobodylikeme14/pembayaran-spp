<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
use App\Models\Kelas;
use DataTables;
use Throwable;
use Storage;

class KelasController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController) {
        $this->pusherController = $pusherController;
    }

    //Page
    public function kelas(Request $request) {
        if ($request->isMethod('post')) {
            try {
                $data = Kelas::all();
                return DataTables::of($data)->make(true);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan data kelas'
                ], 500);
            }
        }
        return view('back.data.kelas');
    }

    //Tambah
    public function kelas_tambah(Request $request) {
        $this->validate($request, [
            'kelas'                 => 'required|max:20|unique:kelas,kode_kelas',
            'kompetensi_keahlian'   => 'required'
        ],[
            'kelas.unique' => 'Data kelas '.$request->kelas.' sudah ada'
        ]);
        try {
            $data = new Kelas;
            $data->kode_kelas = strtoupper($request->kelas);
            $data->kompetensi_keahlian = $request->kompetensi_keahlian;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data kelas berhasil ditambah'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data kelas'
            ], 500);
        }
    }

    //Edit
    public function kelas_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:kelas',
        ], [
            'id.required' => 'Mohon sertakan id kelas',
            'id.exists' => 'Id kelas tidak ditemukan'
        ]);
        try {
            $data = Kelas::where('id', $request->id)->get();
            return Response()->json(['data' => $data]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data kelas'
            ], 500);
        }
    }
    public function kelas_edit(Request $request) {
        $this->validate($request, [
            'kelas'                 => 'required|max:20|unique:kelas,kode_kelas,'.$request->id.',id',
            'kompetensi_keahlian'   => 'required'
        ],[
            'kelas.unique' => 'Data kelas '.$request->kelas.' sudah ada'
        ]);
        try {
            $data = Kelas::find($request->id);
            $data->kode_kelas = strtoupper($request->kelas);
            $data->kompetensi_keahlian = $request->kompetensi_keahlian;
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data kelas berhasil diperbarui'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data kelas'
            ], 500);
        } 
    }

    //Hapus
    public function kelas_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:kelas',
        ], [
            'id.required' => 'Mohon sertakan id kelas',
            'id.exists' => 'Id kelas tidak ditemukan'
        ]);
        try {
            $kodeKelas = Kelas::where('id', $request->id)->value('kode_kelas');
            Kelas::where('id', $request->id)->delete();
            $dataDir = 'storage/export/'. $kodeKelas .'/';
            if (Storage::exists($dataDir)) {
                Storage::deleteDirectory($dataDir);
            }
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data kelas berhasil dihapus'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data kelas'
            ], 500);
        }
    }

    //Hapus semua
    public function kelas_hapus_all () {
        if(Kelas::count() > 0){
            try {
                Schema::disableForeignKeyConstraints();
                Kelas::truncate();
                Schema::enableForeignKeyConstraints();
                $dataDir = 'storage/export';
                if (Storage::exists($dataDir)) {
                    Storage::deleteDirectory($dataDir);
                }
                if ($this->pusherController->isInternetConnected()) {
                    $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
                }
                return Response()->json([
                    'message' => 'Semua data kelas berhasil dihapus'
                ]);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat menghapus data kelas'
                ], 500);
            }
        } else {
            return Response()->json([
                'message' => 'Tidak ada data kelas untuk dihapus'
            ], 404);
        }
    }
}
