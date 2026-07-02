<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JsonBackup;
use Illuminate\Http\Request;

class JsonBackupController extends Controller
{
    public function index(Request $request)
    {
        $query = JsonBackup::orderBy('backup_timestamp', 'desc');
        
        // Filter by sesi_id
        if ($request->has('sesi_id') && $request->sesi_id != '') {
            $query->where('sesi_id_getdata', $request->sesi_id);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('backup_timestamp', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('backup_timestamp', '<=', $request->end_date);
        }
        
        $backups = $query->paginate(25);
        
        return view('admin.json-backup.index', compact('backups'));
    }
    
    public function show($id)
    {
        $backup = JsonBackup::findOrFail($id);
        return view('admin.json-backup.show', compact('backup'));
    }
    
    public function destroy($id)
    {
        $backup = JsonBackup::findOrFail($id);
        $backup->delete();
        
        return redirect()->route('admin.json-backup.index')
            ->with('success', 'JSON backup deleted successfully!');
    }
}
