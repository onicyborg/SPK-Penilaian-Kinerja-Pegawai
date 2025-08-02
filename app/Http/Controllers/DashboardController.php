<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Employees
        $totalEmployees = \App\Models\Employee::count();
        $activeEmployees = \App\Models\Employee::where('status', 'active')->count();
        $inactiveEmployees = $totalEmployees - $activeEmployees;

        // Assessment Periods
        $totalPeriods = \App\Models\AssessmentPeriod::count();
        $periodsByStatus = [
            'draft' => \App\Models\AssessmentPeriod::where('status', 'draft')->count(),
            'active' => \App\Models\AssessmentPeriod::where('status', 'active')->count(),
            'completed' => \App\Models\AssessmentPeriod::where('status', 'completed')->count(),
        ];

        // Latest completed period
        $latestCompletedPeriod = \App\Models\AssessmentPeriod::where('status', 'completed')->orderByDesc('updated_at')->first();
        $topPerformers = [];
        $performanceDistribution = [];
        $employeesAssessed = 0;
        $criteriaCount = 0;
        if ($latestCompletedPeriod) {
            $topPerformers = $latestCompletedPeriod->sawResults()->with('employee')->orderBy('rank')->take(3)->get();
            $performanceDistribution = $latestCompletedPeriod->sawResults()
                ->selectRaw('CASE
                    WHEN final_score >= 0.9 THEN \'Excellent\'
                    WHEN final_score >= 0.8 THEN \'Very Good\'
                    WHEN final_score >= 0.7 THEN \'Good\'
                    WHEN final_score >= 0.6 THEN \'Fair\'
                    ELSE \'Poor\' END as category, COUNT(*) as total')
                ->groupBy('category')->pluck('total', 'category')->toArray();
            $employeesAssessed = $latestCompletedPeriod->getTotalEmployeesAssessed();
            $criteriaCount = $latestCompletedPeriod->getTotalCriteria();
        }

        // Recent logs
        $recentLogs = \App\Models\AssessmentLog::with('user', 'assessmentPeriod')->orderByDesc('created_at')->limit(5)->get();

        return view('dashboard', compact(
            'totalEmployees', 'activeEmployees', 'inactiveEmployees',
            'totalPeriods', 'periodsByStatus',
            'latestCompletedPeriod', 'topPerformers', 'performanceDistribution',
            'employeesAssessed', 'criteriaCount', 'recentLogs'
        ));
    }
}

