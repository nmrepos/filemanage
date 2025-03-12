<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAudit extends Model
{
    //
    protected $fillable = [
        'userName',
        'loginTime',
        'remoteIP',
        'status',
        'latitude',
        'longitude'
    ];
}
