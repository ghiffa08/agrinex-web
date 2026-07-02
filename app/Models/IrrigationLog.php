<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IrrigationLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function valveLogs()
    {
        return $this->hasMany(ValveLog::class);
    }
}
