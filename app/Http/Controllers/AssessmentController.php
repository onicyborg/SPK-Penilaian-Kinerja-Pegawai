<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AssessmentPeriod;
use App\Models\Criteria;
use App\Models\Employee;
use App\Models\EmployeeAssessment;
use App\Models\AssessmentLog;
use App\Models\SawResult;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class AssessmentController extends Controller
{

    // Tampilkan halaman assessment-employee
    public function index($id)
    {
        $periode = AssessmentPeriod::with('criteria')->findOrFail($id);
        $criteria = $periode->criteria;
        // Ambil semua karyawan, join dengan assessment jika ada (dengan status periode assessment active)
        if ($periode->status == 'active') {
            $employees = Employee::where('status', 'active')->get()->map(function ($emp) use ($periode) {
                $emp->assessment = EmployeeAssessment::where('assessment_period_id', $periode->id)
                    ->where('employee_id', $emp->id)
                    ->get();
                return $emp;
            });
        }else{
            //ambil data employee nya dari saw_result
            $employees = Employee::all()->map(function ($emp) use ($periode) {
                $emp->assessment = SawResult::where('assessment_period_id', $periode->id)
                    ->where('employee_id', $emp->id)
                    ->get();
                return $emp;
            });
        }
        return view('assessment.assessment-employee', compact('periode', 'criteria', 'employees'));
    }

    // Simpan assessment karyawan
    public function store(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'score' => 'required|array',
            'score.*' => 'required|integer|min:1|max:5',
        ]);

        $periode = AssessmentPeriod::findOrFail($id);
        $criteria = Criteria::where('assessment_period_id', $periode->id)->get();

        DB::beginTransaction();
        try {
            $employeeAssessments = EmployeeAssessment::where('assessment_period_id', $periode->id)
                ->where('employee_id', $request->employee_id)
                ->get();

            foreach ($criteria as $c) {
                $assessment = $employeeAssessments->firstWhere('criteria_id', $c->id);
                if ($assessment) {
                    $assessment->update([
                        'score' => $request->score[$c->id] ?? 0,
                        'assessed_by' => Auth::id(),
                        'assessed_at' => now(),
                    ]);
                } else {
                    EmployeeAssessment::create([
                        'assessment_period_id' => $periode->id,
                        'employee_id' => $request->employee_id,
                        'criteria_id' => $c->id,
                        'score' => $request->score[$c->id] ?? 0,
                        'assessed_by' => Auth::id(),
                        'assessed_at' => now(),
                    ]);
                }
            }
            // Catat log
            AssessmentLog::createLog(
                $periode->id,
                'Assessment Saved',
                'Assessment saved for employee ID: ' . $request->employee_id . ' With Name: ' . Employee::find($request->employee_id)->name,
                Auth::id()
            );
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Assessment berhasil disimpan.']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan assessment: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Proses seluruh penilaian employee untuk assessment_period menggunakan metode SAW
     * dan simpan hasilnya ke tabel saw_results.
     */
    public function processSAW($assessmentPeriodId)
    {
        $periode = AssessmentPeriod::with('criteria')->findOrFail($assessmentPeriodId);
        $criteria = $periode->criteria;
        $employees = Employee::all();

        // 1. Ambil semua skor per employee per kriteria
        $matrix = [];
        foreach ($employees as $emp) {
            foreach ($criteria as $c) {
                $assessment = EmployeeAssessment::where('assessment_period_id', $periode->id)
                    ->where('employee_id', $emp->id)
                    ->where('criteria_id', $c->id)
                    ->first();
                $matrix[$emp->id][$c->id] = $assessment ? $assessment->score : 0;
            }
        }

        // 2. Normalisasi (skor / skor max per kriteria)
        $maxScores = [];
        foreach ($criteria as $c) {
            $max = 0;
            foreach ($employees as $emp) {
                $max = max($max, $matrix[$emp->id][$c->id]);
            }
            $maxScores[$c->id] = $max > 0 ? $max : 1; // Hindari pembagian 0
        }

        // 3. Hitung skor ter-normalisasi dan weighted score
        $results = [];
        foreach ($employees as $emp) {
            $normalized = [];
            $weighted = [];
            $final = 0;
            foreach ($criteria as $c) {
                $norm = $matrix[$emp->id][$c->id] / $maxScores[$c->id];
                $normalized[$c->id] = round($norm, 4);
                $weight = ($c->weight ?? 0) / 100;
                $weighted[$c->id] = round($norm * $weight, 4);
                $final += $weighted[$c->id];
            }
            $results[$emp->id] = [
                'normalized' => $normalized,
                'weighted' => $weighted,
                'final' => round($final, 4),
            ];
        }

        // 4. Ranking
        uasort($results, function($a, $b) {
            return $b['final'] <=> $a['final'];
        });
        $rank = 1;
        foreach ($results as $empId => &$r) {
            $r['rank'] = $rank++;
        }

        // 5. Simpan ke tabel saw_results
        foreach ($results as $empId => $r) {
            SawResult::updateOrCreate(
                [
                    'assessment_period_id' => $periode->id,
                    'employee_id' => $empId,
                ],
                [
                    'normalized_scores' => $r['normalized'],
                    'weighted_scores' => $r['weighted'],
                    'final_score' => $r['final'],
                    'rank' => $r['rank'],
                    'calculation_details' => [
                        'matrix' => $matrix[$empId],
                        'max_scores' => $maxScores,
                        'criteria_weights' => $criteria->pluck('weight','id')->toArray(),
                    ],
                    'calculated_at' => now(),
                ]
            );
        }

        // 6. Logging
        AssessmentLog::createLog(
            $periode->id,
            'Calculated and Saved',
            'Processed SAW calculation for all employees.',
            Auth::id()
        );

        // 7. Update status assessment period
        $periode->update([
            'status' => 'completed',
        ]);

        // 8. Logging
        sleep(1);
        AssessmentLog::createLog(
            $periode->id,
            'Completed',
            'Assessment period completed.',
            Auth::id()
        );

        // 9. Return response
        return response()->json([
            'success' => true,
            'message' => 'Assessment period completed.',
        ]);
    }
}
