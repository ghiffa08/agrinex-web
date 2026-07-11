<?php

namespace App\Repositories\Contracts;

interface JsonBackupRepositoryInterface
{
    public function createBackup(array $data);

    public function getBackups(array $filters, int $limit);
}
