<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GetdataLog;
use App\Services\Admin\GetdataLogsService;
use Illuminate\Http\Request;

class GetdataLogsController extends Controller
{
    protected $logService;
    
    public function __construct(GetdataLogsService $logService)
    {
        $this->logService = $logService;
    }
    
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'start_date', 'end_date']);
        $logs = $this->logService->getPaginatedLogs($filters, 25);
        
        return view('admin.getdata-logs.index', compact('logs'));
    }
    
    public function show($id)
    {
        $log = $this->logService->getLogWithRelations($id);
        return view('admin.getdata-logs.show', compact('log'));
    }
    
    public function edit($id)
    {
        $log = GetdataLog::findOrFail($id);
        return view('admin.getdata-logs.edit', compact('log'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'node_sukses' => 'required|integer|min:0',
            'node_gagal' => 'required|integer|min:0',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        $this->logService->updateLog($id, $validated);
        
        return redirect()->route('admin.getdata-logs.show', $id)
            ->with('success', 'Getdata log updated successfully!');
    }
    
    public function destroy($id)
    {
        $this->logService->deleteLog($id);
        
        return redirect()->route('admin.getdata-logs.index')
            ->with('success', 'Getdata log deleted successfully!');
    }
}
