<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuperstaffAllowedIp extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ip_address',
        'created_by',
        'updated_by',
    ];
}
