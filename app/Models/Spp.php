<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    protected $table = "spp";
    protected $fillable = [
        "kode_spp",
        "tahun",
        "nominal"
    ];

    public function pembayaran() {
        return $this->hasMany(Pembayaran::class, 'id_spp', 'id');
    }
}
