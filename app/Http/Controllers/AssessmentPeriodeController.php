<?php

namespace App\Http\Controllers;

use App\Models\AssessmentLog;
use App\Models\AssessmentPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AssessmentPeriodeController extends Controller
{
    public function index()
    {
        $periodes = AssessmentPeriod::orderBy('created_at', 'desc')->get();
        return view('assessment-periode', compact('periodes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $periode = AssessmentPeriod::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
        ]);
        AssessmentLog::createLog($periode->id, 'Created', 'Assessment di buat', Auth::user()->id);
        return response()->json(['success' => true, 'periode' => $periode]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $periode = AssessmentPeriod::findOrFail($id);
        $periode->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);
        AssessmentLog::createLog($periode->id, 'Updated', 'Assessment di update', Auth::user()->id);
        return response()->json(['success' => true, 'periode' => $periode]);
    }

    public function destroy($id)
    {
        $periode = AssessmentPeriod::findOrFail($id);
        $periode->delete();
        return response()->json(['success' => true]);
    }
}
