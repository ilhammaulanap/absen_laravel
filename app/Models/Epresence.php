<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Epresence extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_users','type','type','is_approve','waktu','tanggal'
    ];

    public static function get_data_absen(){
        $result = Epresence::select('epresences.*','users.id as id_user','users.name')
                        ->join('users','users.id','=','epresences.id_users')                   
                        // ->join('Epresence as absen_masuk','absen_masuk.id_users','=','users.id')                   
                        ->get();
        return $result;
    }

    public static function get_tgl_absen(){
        $result = Epresence::select('tanggal')
                            ->groupBy('tanggal')
                            ->get();
        return $result;
    }
}
