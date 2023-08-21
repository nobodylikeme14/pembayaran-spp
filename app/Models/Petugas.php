<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Petugas extends Authenticatable
{
    use Notifiable;

    public $incrementing = false; 

    protected $guard = "petugas";

    protected $table = "petugas";

    protected $fillable = [
        "nama",
        "email",
        "username",
        "privilege",
        "password"
    ];

    protected $hidden = [ 'password' ];
}
