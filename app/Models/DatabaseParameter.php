<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DatabaseParameter extends Model
{
    protected $fillable = [
        'namaDB',
        'ipHost',
        'port',
        'driver',
        'reason',
        'operator',
        'checker',
        'supervisor',
        'statusApproval'
    ];
}
