<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JsonBackup extends Model
{
    protected $table = 'json_backup';

    public $timestamps = false;

    protected $fillable = [
        'sesi_id_getdata',
        'json_data',
        'data_size_kb',
        'total_records',
        'node_completeness',
        'getdata_logs_count',
        'sensor_weather_count',
        'sensor_node_count',
        'backup_timestamp'
    ];

    protected $casts = [
        'json_data' => 'array',
        'data_size_kb' => 'float',
        'backup_timestamp' => 'datetime'
    ];
}
