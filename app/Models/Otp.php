<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['username', 'otp', 'expired_at'];
    public $timestamps = true;
}
