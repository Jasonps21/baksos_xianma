<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AmbilBeras extends Model
{
    protected $table = 'tb_ambil_beras';
    public $timestamps = false; // kolom create_date/update_date manual


    protected $fillable = [
        'no_kupon',
        'no_ktp',
        'nama',
        'alamat',
        'tgl_pengambil',
        'status_ambil',
        'create_by',
        'create_date',
        'update_by',
        'update_date',
    ];


    protected static function booted(): void
    {
        static::creating(function ($model) {
            $user = Auth::user();
            $model->create_by = $user ? (string) $user->id : null; // atau $user->name
            $model->create_date = now();
        });


        static::updating(function ($model) {
            $user = Auth::user();
            $model->update_by = $user ? (string) $user->id : null; // atau $user->name
            $model->update_date = now();
        });
    }
}
