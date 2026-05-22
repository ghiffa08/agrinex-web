<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SensorWeatherData;
use Illuminate\Http\Request;

class WeatherDataController extends Controller
{
    public function index(Request $request)
    {
        $query = SensorWeatherData::orderBy('received_at', 'desc');
        
        // Filter by sesi_id
        if ($request->has('sesi_id') && $request->sesi_id != '') {
            $query->where('sesi_id_getdata', $request->sesi_id);
        }
        
        // Filter by date range
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('received_at', '>=', $request->start_date);
        }
        
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('received_at', '<=', $request->end_date);
        }
        
        $weatherData = $query->paginate(25);
        
        return view('admin.weather-data.index', compact('weatherData'));
    }
    
    public function show($id)
    {
        $data = SensorWeatherData::findOrFail($id);
        return view('admin.weather-data.show', compact('data'));
    }
    
    public function edit($id)
    {
        $data = SensorWeatherData::findOrFail($id);
        return view('admin.weather-data.edit', compact('data'));
    }
    
    public function update(Request $request, $id)
    {
        $data = SensorWeatherData::findOrFail($id);
        
        $validated = $request->validate([
            'temp_dht' => 'nullable|numeric',
            'humidity' => 'nullable|numeric|min:0|max:100',
            'light' => 'nullable|numeric',
            'wind' => 'nullable|numeric',
            'rain' => 'nullable|integer|in:0,1',
            'voltage' => 'nullable|numeric',
            'current' => 'nullable|numeric',
            'power' => 'nullable|numeric',
        ]);
        
        $data->update($validated);
        
        return redirect()->route('admin.weather-data.show', $id)
            ->with('success', 'Weather data updated successfully!');
    }
    
    public function destroy($id)
    {
        $data = SensorWeatherData::findOrFail($id);
        $data->delete();
        
        return redirect()->route('admin.weather-data.index')
            ->with('success', 'Weather data deleted successfully!');
    }
}
