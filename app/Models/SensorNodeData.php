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
        'battery_pct',
        'current_ma',
        'power_mw',
        'flow_rate',
        'total_volume_l',
        'temp_c',
        'soil_pct',
        'soil_adc',
        'ai_valve_decision',
        'adaptive_sleep_duration',
        'rssi',
        'ts_counter',
        'received_at'
    ];

    protected $casts = [
        'voltage_v' => 'float',
        'battery_pct' => 'float',
        'current_ma' => 'float',
        'power_mw' => 'float',
        'flow_rate' => 'float',
        'total_volume_l' => 'float',
        'temp_c' => 'float',
        'soil_pct' => 'float',
        'soil_adc' => 'integer',
        'ai_valve_decision' => 'string',
        'adaptive_sleep_duration' => 'integer',
        'rssi' => 'float',
        'ts_counter' => 'integer',
        'received_at' => 'datetime'
    ];

    public function getdataLog()
    {
        return $this->belongsTo(GetdataLog::class, 'sesi_id_getdata', 'sesi_id_getdata');
    }

    public function node() : BelongsTo {
        return $this->belongsTo(Node::class, 'node_id', 'node_id');
    }

    public function getTempDs18Attribute()
    {
        return $this->temp_c;
    }

    public function getMoistAttribute()
    {
        return $this->soil_pct;
    }

    public function getVoltAttribute()
    {
        return $this->voltage_v;
    }

    public function getCurrentAttribute()
    {
        return $this->current_ma;
    }
}