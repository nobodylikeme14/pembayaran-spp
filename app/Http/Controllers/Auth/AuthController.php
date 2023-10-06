<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Throwable;
use Session;
use Auth;

class AuthController extends Controller
{
    public function __construct() {
        $this->middleware('throttle:7,1')->only('loginPost');
    }
    public function login() {
        return view('auth.login');
    }
    public function loginPost(Request $req)  {
        $this->validate($req, [
            'username' => 'required',
            'password' => 'required'
        ], [
            'username.required' => 'Mohon masukkan email / username.',
            'password.required' => 'Mohon masukkan password.',
        ]);
        try {
            $loginWith = filter_var($req->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            $data = [$loginWith  => $req->input('username'), 'password'  => $req->input('password')];
            if (Auth::guard('petugas')->attempt($data)) {
                Session::regenerate();
                if (Auth::user()->privilege == "Administrator") {
                    $respons = ['url' => route('dashboard')];
                } else {
                    $respons = ['url' => route('entri_pembayaran')];
                }
            } elseif (Auth::guard('siswa')->attempt($data)) {
                Session::regenerate();
                $respons = ['url' => route('histori_pembayaran')];
            } else {
                return Response()->json([
                    'message' => 'Email / Username atau Password anda salah.'
                ], 401);
            }
            return Response()->json($respons);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat melakukan proses login. Silahkan coba beberapa saat lagi.'
            ], 500);
        }
    }
    public function logout() {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect()->route('login');
    }
}
