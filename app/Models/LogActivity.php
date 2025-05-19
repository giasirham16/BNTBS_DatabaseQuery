<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogActivity extends Model
{
    protected $fillable = [
        'namaDB',
        'ipHost',
        'port',
        'driver',
        'queryRequest',
        'queryResult',
        'deskripsi',
        'reason',
        'operator',
        'checker',
        'supervisor',
        'action'
    ];
}
