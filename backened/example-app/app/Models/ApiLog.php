<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_data', 
        'response_data', 
        'status_code', 
        'endpoint', 
        'ip_address'
    ];
}
