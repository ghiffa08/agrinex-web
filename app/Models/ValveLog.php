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
        'waktu' => 'datetime',
    ];

    /**
     * Get the irrigate log for this valve log
     */
    public function irrigateLog()
    {
        return $this->belongsTo(IrrigateLog::class, 'sesi_id_irrigate', 'sesi_id_irrigate');
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id', 'id');
    }

    /**
     * Scope for successful valve operations
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed valve operations
     */
    public function scopeFailed($query)
    {
        return $query->where('status', '!=', 'success');
    }

    /**
     * Scope by node
     */
    public function scopeByNode($query, $nodeId)
    {
        return $query->where('node_id', $nodeId);
    }

    /**
     * Get volume in liters
     */
    public function getVolumeLitersAttribute()
    {
        return round($this->volume_ml / 1000, 2);
    }

    /**
     * Get flow rate in ml/sec
     */
    public function getFlowRateAttribute()
    {
        if ($this->durasi_detik == 0) return 0;
        return round($this->volume_ml / $this->durasi_detik, 2);
    }
}
