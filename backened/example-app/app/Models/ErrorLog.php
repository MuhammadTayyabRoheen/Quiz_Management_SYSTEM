<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    protected $fillable = ['error_message', 'file', 'line', 'stack_trace'];
}
