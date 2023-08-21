<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $loginWith = filter_var($req->username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $data = [$loginWith  => $req->input('username'), 'password'  => $req->input('password')];
        if (Auth::guard('petugas')->attempt($data)) {
            Session::regenerate();
            if (Auth::guard('petugas')->user()->privilege == "Administrator") {
                $responses = [ 
                    'status' => 'success', 
                    'url' => route('dashboard')
                ];
            } else {
                $responses = [ 
                    'status' => 'success', 
                    'url' => route('entri_pembayaran')
                ];
            }
        } elseif (Auth::guard('siswa')->attempt($data)) {
            Session::regenerate();
            $responses = [ 
                'status' => 'success', 
                'url' => route('histori_pembayaran')
            ];
        } else {
            $responses = [
                'status' => 'error', 
                'message' => 'Email / username atau password salah.'
            ];
        }
        return Response()->json($responses);
    }
    public function logout() {
        Auth::logout();
        Session::invalidate();
        Session::regenerateToken();
        return redirect()->route('login');
    }
}
