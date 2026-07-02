<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NodeLog extends Model
{
    protected $table = 'node_logs';

    public $timestamps = false;
    
    protected $fillable = [
        'sesi_id',
        'node_id',
        'rssi_dbm',
        'snr_db',
        'signal_quality',
        'status',
        'waktu',
        'type_sesi',
        'keterangan'
    ];

    protected $casts = [
        'sesi_id' => 'integer',
        'node_id' => 'integer',
        'rssi_dbm' => 'integer',
        'snr_db' => 'float',
        'waktu' => 'datetime'
    ];

    /**
     * Get the getdata log for this node log
     */
    public function getdataLog()
    {
        return $this->belongsTo(GetdataLog::class, 'sesi_id', 'sesi_id_getdata');
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id', 'id');
    }

    /**
     * Get the irrigate log for this node log
     */
    public function irrigateLog()
    {
        return $this->belongsTo(IrrigateLog::class, 'sesi_id', 'sesi_id_irrigate');
    }

    /**
     * Scope for successful logs
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * Scope for failed logs
     */
    public function scopeFailed($query)
    {
        return $query->where('status', '!=', 'success');
    }

    /**
     * Scope for getdata sessions
     */
    public function scopeGetdata($query)
    {
        return $query->where('type_sesi', 'getdata');
    }

    /**
     * Scope for irrigate sessions
     */
    public function scopeIrrigate($query)
    {
        return $query->where('type_sesi', 'irrigate');
    }

    /**
     * Get signal strength category
     */
    public function getSignalStrengthAttribute()
    {
        if ($this->rssi_dbm >= -70) return 'Excellent';
        if ($this->rssi_dbm >= -80) return 'Good';
        if ($this->rssi_dbm >= -90) return 'Fair';
        return 'Poor';
    }

    /**
     * Check if signal is good
     */
    public function hasGoodSignal()
    {
        return $this->rssi_dbm >= -80 && $this->snr_db >= 7;
    }
}
