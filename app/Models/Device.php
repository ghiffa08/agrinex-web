<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'mac_address',
        'status',
        'firmware_version',
        'last_seen_at',
    ];

    protected $casts = [
        'last_seen_at' => 'datetime',
    ];

    /**
     * Get the sensor logs for the device.
     */
    public function sensorLogs(): HasMany
    {
        return $this->hasMany(SensorLog::class);
    }
}
