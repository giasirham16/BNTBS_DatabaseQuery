<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalQuery extends Model
{
    protected $fillable = [
        'namaDB',
        'ipHost',
        'port',
        'driver',
        'queryRequest',
        'queryResult',
        'username',
        'password',
        'reason',
        'executedBy',
        'executedRole',
        'statusApproval'
    ];
}
