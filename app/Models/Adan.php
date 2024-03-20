<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adan extends Model
{
    use HasFactory;


    //get today fagir adan
    public static function getTodayFajrAdan()
    {
        return self::where('date', now()->format('m-d'))->first()->fagir;
    }
}
