<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorNodeData extends Model
{
    protected $table = 'sensor_node_data';

    public $timestamps = false;
    
    protected $fillable = [
        'sesi_id_getdata',
        'node_id',
        'voltage_v',
        'current_ma',
        'power_mw',
        'temp_c',
        'soil_pct',
        'soil_adc',
        'ts_counter',
        'received_at'
    ];

    protected $casts = [
        'voltage_v' => 'float',
        'current_ma' => 'float',
        'power_mw' => 'float',
        'temp_c' => 'float',
        'soil_pct' => 'float',
        'soil_adc' => 'integer',
        'ts_counter' => 'integer',
        'received_at' => 'datetime'
    ];

    public function getdataLog()
    {
        return $this->belongsTo(GetdataLog::class, 'sesi_id_getdata', 'sesi_id_getdata');
    }

    public function node() : BelongsTo {
        return $this->belongsTo(Node::class, 'node_id', 'id');
    }
}