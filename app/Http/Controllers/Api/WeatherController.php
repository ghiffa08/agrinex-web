<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\BmkgWeatherService;
use Illuminate\Http\JsonResponse;

class WeatherController extends Controller
{
    protected $bmkgService;

    public function __construct(BmkgWeatherService $bmkgService)
    {
        $this->bmkgService = $bmkgService;
    }

    /**
     * Get weather forecast from BMKG
     *
     * @return JsonResponse
     */
    public function getForecast(): JsonResponse
    {
        try {
            $data = $this->bmkgService->getForecast();
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
