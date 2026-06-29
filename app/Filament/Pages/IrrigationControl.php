<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\IrrigateLog;
use App\Models\ValveLog;
use App\Models\Node;
use Filament\Notifications\Notification;

class IrrigationControl extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static string $view = 'filament.pages.irrigation-control';

    protected static ?string $title = 'Irrigation Control';

    protected static ?string $navigationLabel = 'Irrigation Control';

    protected static ?string $navigationGroup = 'Main';

    protected static ?int $navigationSort = -1;

    protected static ?string $slug = 'irrigation-control';

    // Form inputs binding
    public $deviceId = '';
    public $duration = 300;

    /**
     * Get view data.
     */
    public function getViewData(): array
    {
        // Get active irrigation (valve ON status)
        $activeIrrigation = ValveLog::where('status', 'ON')
            ->with('node')
            ->latest('waktu')
            ->get();

        // Calculate statistics
        $stats = [
            'total_events' => IrrigateLog::count(),
            'today_events' => IrrigateLog::whereDate('waktu_mulai', today())->count(),
            'active_valves' => $activeIrrigation->count(),
        ];

        // Get nodes with irrigation capability (exclude Node 65 - weather station)
        $nodes = Node::where('node_id', '!=', 65)->orderBy('node_id')->get();

        // Get recent logs
        $irrigationLogs = IrrigateLog::latest('waktu_mulai')
            ->limit(10)
            ->get();

        return [
            'activeIrrigation' => $activeIrrigation,
            'stats' => $stats,
            'nodes' => $nodes,
            'irrigationLogs' => $irrigationLogs,
        ];
    }

    /**
     * Trigger manual irrigation.
     */
    public function triggerIrrigation()
    {
        $this->validate([
            'deviceId' => 'required|exists:node,node_id',
            'duration' => 'required|integer|min:1|max:3600',
        ]);

        $sesiId = intval(now()->format('YmdHi'));

        // Create irrigation session
        IrrigateLog::create([
            'sesi_id_irrigate' => $sesiId,
            'waktu_mulai'      => now(),
            'node_sukses'      => 1,
            'node_gagal'       => 0,
            'valve_on_akhir'   => 1,
        ]);

        // Create valve log entry
        ValveLog::create([
            'node_id'          => $this->deviceId,
            'sesi_id_irrigate' => $sesiId,
            'status'           => 'ON',
            'durasi_detik'     => $this->duration,
            'waktu'            => now(),
        ]);

        Notification::make()
            ->title('Irrigation Triggered')
            ->body('Manual irrigation triggered successfully for Device ' . $this->deviceId)
            ->success()
            ->send();

        // Reset form inputs
        $this->deviceId = '';
        $this->duration = 300;
    }
}
