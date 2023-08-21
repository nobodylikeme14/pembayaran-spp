<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Auth;
use Hash;

class AkunController extends Controller
{
    public function akun() {
        return view('back.akun');
    }

    public function akun_simpan(Request $request) {
        $this->validate($request, [
            'nama'      => 'required|max:50',
            'email'     => 'required|max:50|unique:petugas,email,'.Auth::user()->id.',id',
            'username'  => 'required|max:50|unique:petugas,username,'.Auth::user()->id.',id',
            'password'  => 'nullable|confirmed|min:6|max:60',
        ], [
            'email.unique' => 'Akun dengan email ini sudah ada',
            'username.unique' => 'Akun dengan username ini sudah ada',
        ]);
        $data = Petugas::find(Auth::user()->id);
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->username = $request->username;
        if ($request->filled('password')) {
            $data->password = Hash::make($request->password);   
        }
        $data->save();
        $responses = [
            'status' => 'success', 
            'message' => 'Info akun berhasil diperbarui'
        ];
        return Response()->json($responses);
    }
}
