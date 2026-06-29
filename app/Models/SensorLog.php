<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SensorLog extends Model
{
    use HasFactory;

    /**
     * Disable updated_at timestamp.
     */
    const UPDATED_AT = null;

    protected $fillable = [
        'device_id',
        'temperature',
        'humidity',
        'soil_moisture',
        'battery_level',
        'created_at',
    ];

    /**
     * Get the device that owns the sensor log.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
