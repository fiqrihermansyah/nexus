<?php

namespace App\Http\Controllers;

use App\Models\MemoRequest;
use App\Models\JobSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Memo stats
        $memoStats = MemoRequest::getStatusCounts();

        $recentMemos = MemoRequest::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $monthlyData = MemoRequest::select(
                DB::raw('MONTH(received_date) as month'),
                DB::raw('YEAR(received_date) as year'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "Done" THEN 1 ELSE 0 END) as done'),
                DB::raw('SUM(CASE WHEN status = "Pending" THEN 1 ELSE 0 END) as pending')
            )
            ->where('received_date', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('year', 'month')
            ->orderBy('year')->orderBy('month')
            ->get();

        // Job schedule stats
        $jobStats = JobSchedule::getStatusCounts();

        $recentJobs = JobSchedule::with('creator')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Jobs by frequency breakdown
        $jobsByDivision = JobSchedule::select('division', DB::raw('COUNT(*) as total'))
            ->groupBy('division')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'memoStats', 'recentMemos', 'monthlyData',
            'jobStats', 'recentJobs', 'jobsByDivision'
        ));
    }
}
