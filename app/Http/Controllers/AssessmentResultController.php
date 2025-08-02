<?php

namespace App\Http\Controllers;

use App\Models\AssessmentPeriod;
use App\Models\SawResult;
use Illuminate\Http\Request;

class AssessmentResultController extends Controller
{
    public function results($id)
    {
        $periode = AssessmentPeriod::findOrFail($id);
        $saw_results = SawResult::with('employee')
            ->where('assessment_period_id', $id)
            ->orderBy('rank')
            ->get();
        return view('assessment.results', compact('periode', 'saw_results'));
    }
}
