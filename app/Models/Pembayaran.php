<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = "pembayaran";
    protected $fillable = [
        "kode_pembayaran",
        "id_siswa",
        "id_petugas",
        "tanggal_bayar",
        "bulan_dibayar",
        "id_spp",
        "jumlah_bayar"
    ];
}
