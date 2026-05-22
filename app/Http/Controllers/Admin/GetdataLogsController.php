<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GetdataLog;
use Illuminate\Http\Request;

class GetdataLogsController extends Controller
{
    public function index(Request $request)
    {
        $query = GetdataLog::orderBy('waktu_mulai', 'desc');
        
        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('waktu_mulai', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('waktu_mulai', '<=', $request->end_date);
        }
        
        $logs = $query->paginate(25);
        
        return view('admin.getdata-logs.index', compact('logs'));
    }
    
    public function show($id)
    {
        $log = GetdataLog::with(['sensorNodeData', 'sensorWeatherData', 'nodeLogs'])->findOrFail($id);
        return view('admin.getdata-logs.show', compact('log'));
    }
    
    public function edit($id)
    {
        $log = GetdataLog::findOrFail($id);
        return view('admin.getdata-logs.edit', compact('log'));
    }
    
    public function update(Request $request, $id)
    {
        $log = GetdataLog::findOrFail($id);
        
        $validated = $request->validate([
            'node_sukses' => 'required|integer|min:0',
            'node_gagal' => 'required|integer|min:0',
            'status' => 'required|string|max:255',
            'keterangan' => 'nullable|string',
        ]);
        
        $log->update($validated);
        
        return redirect()->route('admin.getdata-logs.show', $id)
            ->with('success', 'Getdata log updated successfully!');
    }
    
    public function destroy($id)
    {
        $log = GetdataLog::findOrFail($id);
        $log->delete();
        
        return redirect()->route('admin.getdata-logs.index')
            ->with('success', 'Getdata log deleted successfully!');
    }
}
