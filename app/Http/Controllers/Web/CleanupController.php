<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CleanupService;
use Illuminate\Http\Request;

class CleanupController extends Controller
{
    protected $cleanupService;

    public function __construct(CleanupService $cleanupService)
    {
        $this->cleanupService = $cleanupService;
    }

    /**
     * Show cleanup page
     */
    public function index()
    {
        return view('cleanup');
    }

    /**
     * Execute cleanup
     */
    public function execute(Request $request)
    {
        $days = $request->input('days', 90);
        $confirm = $request->input('confirm', 'no');

        if ($confirm !== 'yes') {
            return back()->with('error', 'Please confirm cleanup operation');
        }

        try {
            $result = $this->cleanupService->cleanup($days);

            return back()->with('success', 'Cleanup completed successfully')
                        ->with('result', $result);

        } catch (\Exception $e) {
            return back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }
}
