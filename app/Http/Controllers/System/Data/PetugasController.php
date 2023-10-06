<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Petugas;
use DataTables;
use Throwable;
use Hash;

class PetugasController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController) {
        $this->pusherController = $pusherController;
    }

    //Page
    public function petugas(Request $request) {
        if ($request->isMethod('post')) {
            try {
                $data = Petugas::where('privilege', '!=', "Administrator")->get();
                return DataTables::of($data)->make(true);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat mendapatkan data petugas'
                ], 500);
            }
        }
        return view('back.data.petugas');
    }

    //Tambah
    public function petugas_tambah(Request $request) {
        $this->validate($request, [
            'nama'      => 'required|max:50',
            'email'     => 'required|max:50|unique:petugas,email',
            'username'  => 'required|max:50|unique:petugas,username',
            'password'  => 'required|confirmed|min:6|max:60',
        ], [
            'email.unique' => 'Data petugas dengan email ini sudah ada',
            'username.unique' => 'Data petugas dengan username ini sudah ada',
        ]);
        try {
            $data = new Petugas;
            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->username = $request->username;
            $data->password = Hash::make($request->password);
            $data->privilege = "Petugas";
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data petugas berhasil ditambah'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data petugas'
            ], 500);
        }
    }

    //Edit
    public function petugas_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:petugas',
        ], [
            'id.required' => 'Mohon sertakan id petugas',
            'id.exists' => 'Id petugas tidak ditemukan'
        ]);
        try {
            if (Petugas::where('id', $request->id)->value('privilege') != "Administrator") {
                $data = Petugas::where('id', $request->id)->get();
                return Response()->json(['data' => $data]);
            }
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data petugas'
            ], 500);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data petugas'
            ], 500);
        }
    }
    public function petugas_edit(Request $request) {
        $this->validate($request, [
            'nama'      => 'required|max:50',
            'email'     => 'required|max:50|unique:petugas,email,'.$request->id.',id',
            'username'  => 'required|max:50|unique:petugas,username,'.$request->id.',id',
            'password'  => 'nullable|confirmed|min:6|max:60',
        ], [
            'email.unique' => 'Data petugas dengan email ini sudah ada',
            'username.unique' => 'Data petugas dengan username ini sudah ada',
        ]);
        try {
            $data = Petugas::find($request->id);
            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->username = $request->username;
            if ($request->filled('password')) {
                $data->password = Hash::make($request->password);   
            }
            $data->save();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            return Response()->json([
                'message' => 'Data petugas berhasil diperbarui'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data petugas'
            ], 500);
        }
    }

    //Hapus
    public function petugas_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:petugas',
        ], [
            'id.required' => 'Mohon sertakan id petugas',
            'id.exists' => 'Id petugas tidak ditemukan'
        ]);
        try {
            if (Petugas::where('id', $request->id)->value('privilege') != "Administrator") {
                Petugas::where('id', $request->id)->delete();
                if ($this->pusherController->isInternetConnected()) {
                    $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
                }
                return Response()->json([
                    'message' => 'Data petugas berhasil dihapus'
                ]);
            }
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mendapatkan data petugas'
            ], 500);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat menghapus data petugas'
            ], 500);
        }
    }
}
