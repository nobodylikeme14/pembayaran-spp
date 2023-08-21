<?php

namespace App\Http\Controllers\System\Data;

use App\Http\Controllers\Controller;
use App\Http\Controllers\System\PusherController;
use Illuminate\Http\Request;
use App\Models\Petugas;
use DataTables;
use Session;
use Hash;

class PetugasController extends Controller
{
    //Pusher
    private $pusherController;
    public function __construct(PusherController $pusherController)
    {
        $this->pusherController = $pusherController;
    }

    //Page
    public function petugas() {
        return view('back.data.petugas');
    }

    //Data
    public function petugas_data() {
        $data = Petugas::where('privilege', '!=', "Administrator")->get();
        return DataTables::of($data)->make(true);
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
        $data = new Petugas;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->username = $request->username;
        $data->password = Hash::make($request->password);
        $data->privilege = "Petugas";
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data petugas berhasil disimpan'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses); 
    }

    //Edit
    public function petugas_detail(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:petugas',
        ], [
            'id.required' => 'Mohon sertakan id petugas',
            'id.exists' => 'Id petugas tidak ditemukan'
        ]);
        if (Petugas::where('id', $request->id)->value('privilege') != "Administrator") {
            $data = Petugas::where('id', $request->id)->get();
            $responses = [
                'status' => 'success', 
                'data' => $data
            ];
            return Response()->json($responses);
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
        $data = Petugas::find($request->id);
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->username = $request->username;
        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);   
        }
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Data petugas berhasil diperbarui'
        ];
        if ($this->pusherController->isInternetConnected()) {
            $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
        }
        return Response()->json($responses);
    }

    //Hapus
    public function petugas_hapus(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:petugas',
        ], [
            'id.required' => 'Mohon sertakan id petugas',
            'id.exists' => 'Id petugas tidak ditemukan'
        ]);
        if (Petugas::where('id', $request->id)->value('privilege') != "Administrator") {
            Petugas::where('id', $request->id)->delete();
            if ($this->pusherController->isInternetConnected()) {
                $this->pusherController->triggerPusherEvent('dashboard-data', 'update-dashboard-data');
            }
            $responses = [
                'status' => 'success', 
                'message' => 'Data petugas berhasil dihapus'
            ];
            return Response()->json($responses);
        }
    }
}
