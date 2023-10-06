<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Throwable;
use Auth;
use Hash;

class AkunController extends Controller
{
    public function akun(Request $request) {
        if ($request->isMethod('post')) {
            $this->validate($request, [
                'nama'      => 'required|max:50',
                'email'     => 'required|max:50|unique:petugas,email,'.Auth::user()->id.',id',
                'username'  => 'required|max:50|unique:petugas,username,'.Auth::user()->id.',id',
                'password'  => 'nullable|confirmed|min:6|max:60',
            ], [
                'email.unique' => 'Akun dengan email ini sudah ada',
                'username.unique' => 'Akun dengan username ini sudah ada',
            ]);
            try {
                $data = Petugas::find(Auth::user()->id);
                $data->nama = $request->nama;
                $data->email = $request->email;
                $data->username = $request->username;
                if ($request->filled('password')) {
                    $data->password = Hash::make($request->password);   
                }
                $data->save();
                return Response()->json([
                    'message' => 'Info akun berhasil diperbarui'
                ]);
            } catch (Throwable $th) {
                return Response()->json([
                    'message' => 'Terjadi kesalahan saat menyimpan info akun'
                ], 500);
            }
        }
        return view('back.akun');
    }
}
