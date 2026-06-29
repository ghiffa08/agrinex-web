<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValveLog extends Model
{
    protected $table = 'valve_logs';

    public $timestamps = false;

    protected $fillable = [
        'node_id',
        'sesi_id_irrigate',
        'durasi_detik',
        'volume_air',
        'rata_rata',
        'pulse',
        'status',
        'waktu'
    ];

    protected $casts = [
        'node_id' => 'integer',
        'sesi_id_irrigate' => 'integer',
        'durasi_detik' => 'integer',
        'volume_air' => 'float',
        'rata_rata' => 'float',
        'pulse' => 'integer',
        'waktu' => 'datetime'
    ];

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id', 'node_id');
    }

    public function irrigateLog()
    {
        return $this->belongsTo(IrrigateLog::class, 'sesi_id_irrigate', 'sesi_id_irrigate');
    }

    /**
     * Accessor aliases for schema compatibility
     */
    public function getDeviceIdAttribute()
    {
        return $this->node_id;
    }

    public function getSessionIdAttribute()
    {
        return $this->sesi_id_irrigate;
    }

    public function getLoggedAtAttribute()
    {
        return $this->waktu;
    }
}
