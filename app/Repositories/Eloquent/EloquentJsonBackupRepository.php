<?php

namespace App\Repositories\Eloquent;

use App\Models\JsonBackup;
use App\Repositories\Contracts\JsonBackupRepositoryInterface;

class EloquentJsonBackupRepository implements JsonBackupRepositoryInterface
{
    public function createBackup(array $data): JsonBackup
    {
        return JsonBackup::create($data);
    }

    public function getBackups(array $filters, int $limit): \Illuminate\Database\Eloquent\Collection
    {
        $query = JsonBackup::query()->orderBy('backup_timestamp', 'desc');

        if (!empty($filters['sesi_id_getdata'])) {
            $query->where('sesi_id_getdata', $filters['sesi_id_getdata']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('backup_timestamp', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('backup_timestamp', '<=', $filters['date_to']);
        }

        return $query->limit($limit)->get();
    }
}
