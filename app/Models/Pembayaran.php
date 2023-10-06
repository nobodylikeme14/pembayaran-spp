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

    public function siswa() {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    public function spp() {
        return $this->belongsTo(Spp::class, 'id_spp', 'id');
    }

    public function petugas() {
        return $this->belongsTo(Petugas::class, 'id_petugas', 'id');
    }
}
