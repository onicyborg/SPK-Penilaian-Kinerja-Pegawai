<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentLog;
use App\Models\AssessmentPeriod;

class AssessmentLogsController extends Controller
{
    public function index($periodeId)
    {
        $periode = AssessmentPeriod::findOrFail($periodeId);
        $logs = AssessmentLog::with('user')->where('assessment_period_id', $periodeId)->orderBy('created_at', 'desc')->get();
        return view('assessment.logs', compact('periode', 'logs'));
    }
}
