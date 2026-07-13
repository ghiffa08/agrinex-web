<?php

namespace App\Repositories\Eloquent;

use App\Models\IrrigationLog;
use App\Models\ValveLog;
use App\Repositories\Contracts\IrrigationRepositoryInterface;
use Carbon\Carbon;

class EloquentIrrigationRepository implements IrrigationRepositoryInterface
{
    public function createIrrigationLog(array $data)
    {
        return IrrigationLog::create($data);
    }

    public function createIrrigateLog(array $data)
    {
        // Map legacy irrigate_logs fields to new irrigation_logs schema
        return IrrigationLog::create([
            'session_id' => $data['sesi_id_irrigate'],
            'started_at' => $data['waktu_mulai'] ?? now(),
            'ended_at' => $data['waktu_akhir'] ?? $data['waktu_selesai'] ?? null,
            'success_count' => $data['node_sukses'] ?? 0,
            'failed_count' => $data['node_gagal'] ?? 0,
            'valve_on_count' => $data['valve_on_akhir'] ?? 0,
        ]);
    }

    public function createValveLog(array $data)
    {
        return ValveLog::create($data);
    }

    public function getLatestCompleted()
    {
        return IrrigationLog::with('valveLogs')
            ->where('status', 'completed')
            ->orderBy('started_at', 'desc')
            ->first();
    }

    public function getHistory($filters, $limit)
    {
        $query = IrrigationLog::query();

        if (!empty($filters['start_date'])) {
            $query->where('started_at', '>=', $filters['start_date']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->with('valveLogs')
            ->orderBy('started_at', 'desc')
            ->limit($limit)
            ->get();
    }
}
