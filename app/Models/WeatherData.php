<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherData extends Model
{
    use HasFactory;

    public $timestamps = false; // Table doesn't have created_at/updated_at

    protected $guarded = ['id'];

    public function session()
    {
        return $this->belongsTo(DataSession::class, 'data_session_id');
    }

    public function device()
    {
        return $this->belongsTo(Device::class);
    }
}
