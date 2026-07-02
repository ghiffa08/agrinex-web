<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GetdataLog extends Model
{
    protected $table = 'getdata_logs';
    
    protected $primaryKey = 'id';

    public $timestamps = false;
    
    protected $fillable = [
    'sesi_id_getdata',
    'waktu_mulai',
    'waktu_selesai',
    // 'jumlah_node',
    'node_sukses',
    'node_gagal',
    'status',
    'keterangan'
    ];

    protected $casts = [
        'sesi_id_getdata' => 'integer',
        'jumlah_node' => 'integer',
        'node_sukses' => 'integer',
        'node_gagal' => 'integer',
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    /**
     * Get sensor weather data for this session
     */
    public function sensorWeatherData()
    {
        return $this->hasMany(SensorWeatherData::class, 'sesi_id_getdata', 'sesi_id_getdata');
    }

    /**
     * Get sensor node data for this session
     */
    public function sensorNodeData()
    {
        return $this->hasMany(SensorNodeData::class, 'sesi_id_getdata', 'sesi_id_getdata');
    }

    /**
     * Get node logs for this session
     */
    public function nodeLogs()
    {
        return $this->hasMany(NodeLog::class, 'sesi_id', 'sesi_id_getdata');
    }

    /**
     * Scope for successful sessions
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed')
                     ->where('node_gagal', 0);
    }

    /**
     * Scope for failed sessions
     */
    public function scopeFailed($query)
    {
        return $query->where('node_gagal', '>', 0);
    }

    /**
     * Get success rate percentage
     */
    public function getSuccessRateAttribute()
    {
        if ($this->jumlah_node == 0) return 0;
        return round(($this->node_sukses / $this->jumlah_node) * 100, 2);
    }

    /**
     * Get session duration in seconds
     */
    public function getDurationAttribute()
    {
        if (!$this->waktu_mulai || !$this->waktu_selesai) return 0;
        return $this->waktu_selesai->diffInSeconds($this->waktu_mulai);
    }
}
