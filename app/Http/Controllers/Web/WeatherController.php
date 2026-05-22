<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\SensorWeatherData;
use Illuminate\Http\Request;

class WeatherController extends Controller
{
    /**
     * Display weather dashboard
     */
    public function index()
    {
        // Get latest weather data (Node 65)
        $latestWeather = SensorWeatherData::where('node_id', 65)
            ->latest('received_at')
            ->first();

        // Get weather data for last 24 hours
        $weatherData24h = SensorWeatherData::where('node_id', 65)
            ->where('received_at', '>=', now()->subDay())
            ->orderBy('received_at', 'asc')
            ->get();

        // Get weather data for last 7 days
        $weatherData7d = SensorWeatherData::where('node_id', 65)
            ->where('received_at', '>=', now()->subDays(7))
            ->orderBy('received_at', 'asc')
            ->get();

        // Calculate statistics
        $stats = [
            'current_temp' => $latestWeather->temp_dht ?? 0,
            'current_humidity' => $latestWeather->humidity ?? 0,
            'avg_temp_24h' => $weatherData24h->avg('temp_dht'),
            'avg_humidity_24h' => $weatherData24h->avg('humidity'),
            'max_light_24h' => $weatherData24h->max('light'),
            'total_readings' => SensorWeatherData::where('node_id', 65)->count(),
        ];

        // Check rain status
        $stats['rain_status'] = $latestWeather && $latestWeather->rain > 0 ? 'Raining' : 'No Rain';

        return view('weather.index', compact('latestWeather', 'weatherData24h', 'weatherData7d', 'stats'));
    }

    /**
     * Get weather history
     */
    public function history(Request $request)
    {
        $startDate = $request->input('start_date', now()->subDays(7));
        $endDate = $request->input('end_date', now());

        $weatherHistory = SensorWeatherData::where('node_id', 65)
            ->whereBetween('received_at', [$startDate, $endDate])
            ->orderBy('received_at', 'desc')
            ->paginate(100);

        return view('weather.history', compact('weatherHistory', 'startDate', 'endDate'));
    }

    /**
     * Get weather data for charts (API endpoint)
     */
    public function chartData(Request $request)
    {
        $hours = $request->input('hours', 24);

        $data = SensorWeatherData::where('node_id', 65)
            ->where('received_at', '>=', now()->subHours($hours))
            ->orderBy('received_at', 'asc')
            ->get();

        return response()->json([
            'labels' => $data->pluck('received_at')->map(function($date) {
                return $date->format('H:i');
            }),
            'temperature' => $data->pluck('temp_dht'),
            'humidity' => $data->pluck('humidity'),
            'light' => $data->pluck('light'),
            'wind' => $data->pluck('wind'),
            'rain' => $data->pluck('rain'),
        ]);
    }
}
