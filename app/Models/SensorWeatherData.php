<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorWeatherData extends Model
{
    protected $table = 'sensor_weather_data';

    public $timestamps = false;
    
    protected $fillable = [
        'sesi_id_getdata',
        'node_id',
        'voltage',
        'current',
        'power',
        'light',
        'rain',
        'rain_adc',
        'wind',
        'wind_pulse',
        'humidity',
        'temp_dht',
        'ts_counter',
        'received_at'
    ];

    protected $casts = [
        'sesi_id_getdata' => 'integer',
        'node_id' => 'integer',
        'voltage' => 'float',
        'current' => 'float',
        'power' => 'float',
        'light' => 'float',
        'rain' => 'float',
        'rain_adc' => 'integer',
        'wind' => 'float',
        'wind_pulse' => 'integer',
        'humidity' => 'float',
        'temp_dht' => 'float',
        'ts_counter' => 'integer',
        'received_at' => 'datetime'
    ];

    /**
     * Get the getdata log for this weather data
     */
    public function getdataLog()
    {
        return $this->belongsTo(GetdataLog::class, 'sesi_id_getdata', 'sesi_id_getdata');
    }

    public function node()
    {
        return $this->belongsTo(Node::class, 'node_id', 'id');
    }

    /**
     * Check if it's raining
     */
    public function isRaining()
    {
        return $this->rain > 0 || $this->rain_adc < 500;
    }

    /**
     * Get wind speed category
     */
    public function getWindCategoryAttribute()
    {
        if ($this->wind < 2) return 'Calm';
        if ($this->wind < 5) return 'Light breeze';
        if ($this->wind < 10) return 'Moderate breeze';
        if ($this->wind < 15) return 'Strong breeze';
        return 'Gale';
    }

    /**
     * Get light intensity category
     */
    public function getLightCategoryAttribute()
    {
        if ($this->light < 200) return 'Dark';
        if ($this->light < 500) return 'Low light';
        if ($this->light < 800) return 'Moderate';
        return 'Bright';
    }
}
