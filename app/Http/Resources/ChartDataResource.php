<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Carbon\Carbon;

class ChartDataResource extends ResourceCollection
{
    protected $type;

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function toArray($request)
    {
        $type = $this->type ?? 'all';
        $chartData = [
            'temperature' => [],
            'humidity' => [],
            'light' => [],
            'soilMoisture' => [],
            'voltage' => [],
            'power' => []
        ];

        foreach ($this->collection as $session) {
            $sessionTime = Carbon::parse($session->waktu_mulai);
            $timestamp = $sessionTime->format('H:i');
            
            $weather = null;
            $weatherList = $session->sensorWeatherData;
            if ($weatherList && $weatherList->count() > 0) {
                $weather = $weatherList->first();
                
                if ($type === 'all' || $type === 'temperature') {
                    $chartData['temperature'][] = [
                        'time' => $timestamp,
                        'value' => (float) $weather->temp_dht,
                        'temperature' => (float) $weather->temp_dht
                    ];
                }
                
                if ($type === 'all' || $type === 'humidity') {
                    $chartData['humidity'][] = [
                        'time' => $timestamp,
                        'value' => (float) $weather->humidity,
                        'humidity' => (float) $weather->humidity
                    ];
                }
                
                if ($type === 'all' || $type === 'light') {
                    $chartData['light'][] = [
                        'time' => $timestamp,
                        'value' => (float) $weather->light,
                        'radiation' => (float) $weather->light
                    ];
                }
                
                if ($type === 'all' || $type === 'voltage') {
                    $chartData['voltage'][] = [
                        'time' => $timestamp,
                        'value' => (float) $weather->voltage,
                        'voltage' => (float) $weather->voltage
                    ];
                }
                
                if ($type === 'all' || $type === 'power') {
                    $chartData['power'][] = [
                        'time' => $timestamp,
                        'value' => (float) $weather->power,
                        'power' => (float) $weather->power
                    ];
                }
            }
            
            if ($type === 'all' || $type === 'soilMoisture') {
                $soilValues = [];
                foreach ($session->sensorNodeData as $node) {
                    $sensorId = "SM{$node->node_id}";
                    
                    if (!isset($chartData['soilMoisture'][$sensorId])) {
                        $chartData['soilMoisture'][$sensorId] = [];
                    }
                    
                    $chartData['soilMoisture'][$sensorId][] = [
                        'time' => $timestamp,
                        'value' => (float) $node->soil_pct
                    ];
                    
                    $soilValues[] = (float) $node->soil_pct;
                }
                
                if (!empty($soilValues)) {
                    if (!isset($chartData['soil'])) {
                        $chartData['soil'] = [];
                    }
                    $chartData['soil'][] = [
                        'time' => $timestamp,
                        'average' => array_sum($soilValues) / count($soilValues)
                    ];
                }
            }
            
            if (($type === 'all' || $type === 'water') && $weather && $weather->level !== null) {
                if (!isset($chartData['water'])) {
                    $chartData['water'] = [];
                }
                $chartData['water'][] = [
                    'time' => $timestamp,
                    'level' => (float) $weather->level
                ];
            }
        }
        
        return $chartData;
    }
}
