<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Rules\NotSameAsOldPassword;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Carbon\Carbon;
use Throwable;
use Validator;
use Session;
use Cache;
use Auth;
use Hash;
use Mail;
use Str;
use DB;

class ResetPasswordController extends Controller
{
    public function __construct() {
        $this->middleware('throttle:7,1')->only(['lupa_passwordPost', 'reset_passwordPost']);
    }
    //Lupa Password Page
    public function lupa_password() {
        return view('password.reset');
    }
    //Form Kirim Link
    public function lupa_passwordPost(Request $request) {
        $this->validate($request, [
            'email' => 'required|email|exists:petugas,email'
        ], [
            'email.required' => 'Mohon masukkan alamat email anda.',
            'email.email'    => 'Mohon masukkan alamat email yang valid.',
            'email.exists'   => 'Maaf, email yang anda masukkan tidak ditemukan.'
        ]);
        try {
            $emailAddress = $request->email;
            $throttleKey = 'kirim_link_reset_' . $emailAddress;
            $otpThrottleTime = 2;
            $lastAttemptTime = Cache::get($throttleKey);
            if ($lastAttemptTime && now()->diffInMinutes($lastAttemptTime) < $otpThrottleTime) {
                return Response()->json([
                    'message' => 'Link reset password sudah dikirim. Mohon tunggu beberapa saat sebelum mengirim ulang.'
                ], 429);
            }
            $token = Str::random(120);
            DB::table('password_resets')->insert([
                'email'      => $emailAddress,
                'token'      => $token,
                'created_at' => Carbon::now()
            ]);
            Mail::send('password.email',['token' => $token], function($message) use ($emailAddress) {
                $message->to($emailAddress);
                $message->subject('EduCashLog Reset Password Link');
            });
            Cache::put($throttleKey, now(), now()->addMinutes($otpThrottleTime));
            return Response()->json([
                'message' => 'Kami telah mengirim link reset password ke alamat email Anda. Link reset password berlaku selama 1 jam.'
            ]);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mengirim link reset password. Silahkan coba beberapa saat lagi.'
            ], 500);
        }
    }

    //Reset Password Page
    public function reset_password($token) {
        $email = DB::table('password_resets')->where('token', $token)->value('email');
        $newestToken = DB::table('password_resets')->where('email', $email)->orderByDesc('created_at')->first();
        if ($email && $newestToken) {
            $validityTimestamp = Carbon::parse($newestToken->created_at)->addHour();
            if (Carbon::now()->lte($validityTimestamp) && $token === $newestToken->token) {
                return view('password.verify', ['token' => $token, 'email' => $email]);
            }
        }
        return abort(404);
    }
    //Form Reset Password
    public function reset_passwordPost(Request $request) {
        $oldPassword = Petugas::where('email', $request->email)->value('password');
        $this->validate($request, [
            'password'              => ['required', 'confirmed', 'min:6', new NotSameAsOldPassword($oldPassword)],
            'password_confirmation' => 'required'
        ], [
            'password.required'  => 'Mohon masukkan password baru.',
            'password.min'       => 'Panjang password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password_confirmation.required' => 'Mohon masukkan konfirmasi password baru.'
        ]);
        try {
            $email = DB::table('password_resets')->where('token', $request->token)->value('email');
            $newestToken = DB::table('password_resets')->where('email', $request->email)->orderByDesc('created_at')->first();
            if ($email && $newestToken) {
                $validityTimestamp = Carbon::parse($newestToken->created_at)->addHour();
                if (Carbon::now()->lte($validityTimestamp) && $request->token === $newestToken->token) {
                    Petugas::where('email', $request->email)->update([
                        'password' => Hash::make($request->password),
                        'updated_at' => Carbon::now()
                    ]);
                    DB::table('password_resets')->where(['email'=> $request->email])->delete();
                    $data = ['email'  => $request->email, 'password'  => $request->password];
                    Auth::attempt($data);
                    Session::regenerate();
                    return response()->json([
                        'url' => route('dashboard', [
                            'message' => 'Password akun anda berhasil direset.'
                        ])
                    ]);
                }
            }
            return Response()->json([
                'message' => 'Link reset password tidak valid atau sudah kadaluarsa. Silahkan tekan tombol Kembali dan ulangi proses reset password.'
            ], 500);
        } catch (Throwable $th) {
            return Response()->json([
                'message' => 'Terjadi kesalahan saat mereset password akun anda. Silahkan coba beberapa saat lagi.'
            ], 500);
        }
    }
}
