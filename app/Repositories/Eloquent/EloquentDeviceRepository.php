<?php

namespace App\Repositories\Eloquent;

use App\Models\Device;
use App\Repositories\Contracts\DeviceRepositoryInterface;

class EloquentDeviceRepository implements DeviceRepositoryInterface
{
    public function allDevices()
    {
        return Device::with(['sensorData' => function($query) {
            $query->latest('recorded_at')->limit(1);
        }])->orderBy('id')->get();
    }

    public function allNodes()
    {
        // Legacy method - now uses devices table
        return Device::with('lahanPantau')->orderBy('id')->get();
    }

    public function findById($id)
    {
        return Device::find($id);
    }

    public function findNodeById($nodeId)
    {
        // Legacy method - node_id column doesn't exist anymore
        // Treat nodeId as device.id
        return Device::find($nodeId);
    }

    public function firstOrCreateNode(array $search, array $values)
    {
        // Legacy method - devices table no longer has node_id column
        // Simply check if device with ID exists
        if (isset($search['node_id'])) {
            $deviceId = $search['node_id'];
            $device = Device::find($deviceId);
            
            if (!$device) {
                // Device doesn't exist - log warning but don't auto-create
                // (devices should be pre-registered via admin panel)
                \Log::warning("Device ID {$deviceId} not found - data will be saved but device should be registered");
            }
            
            return $device;
        }
        
        return null;
    }

    public function firstOrCreateDevice(array $search, array $values)
    {
        return Device::firstOrCreate($search, $values);
    }

    public function update($id, array $data)
    {
        $device = Device::findOrFail($id);
        $device->update($data);
        return $device;
    }
}
