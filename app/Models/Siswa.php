<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Authenticatable
{
    use Notifiable;

    public $incrementing = false;

    protected $guard = "siswa";

    protected $table = "siswa";
    
    protected $fillable = [
        "nisn",
        "nis",
        "nama",
        "kode_kelas",
        "nomor_hp",
        "alamat",
        "email",
        "username",
        "privilege",
        "password"
    ];

    protected $hidden = [ 'password' ];

    public function pembayaran() {
        return $this->hasMany(Pembayaran::class, 'id_siswa', 'id');
    }
}
