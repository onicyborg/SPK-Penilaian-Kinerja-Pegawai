<?php

namespace App\Http\Controllers;

use App\Models\AssessmentLog;
use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\AssessmentPeriod;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CriteriaController extends Controller
{
    public function index($id)
    {
        $criteria = Criteria::where('assessment_period_id', $id)->get();
        $periode = AssessmentPeriod::findOrFail($id);
        return view('assessment.setting-criteria', compact('criteria', 'periode'));
    }

    public function store(Request $request, $id)
    {
        $action = $request->input('action');
        $names = $request->input('name', []);
        $descs = $request->input('description', []);
        $weights = $request->input('weight', []);

        // Validasi minimal 1 kriteria
        if (count($names) < 1) {
            return response()->json(['success' => false, 'message' => 'Minimal 1 kriteria diperlukan']);
        }

        // Validasi field
        foreach ($names as $i => $name) {
            if (!$name || !isset($weights[$i]) || $weights[$i] === null || $weights[$i] === '') {
                return response()->json(['success' => false, 'message' => 'Nama dan bobot wajib diisi']);
            }
        }

        // Jika submit, validasi total bobot
        if ($action === 'submit') {
            $total = array_sum(array_map('intval', $weights));
            if ($total !== 100) {
                return response()->json(['success' => false, 'message' => 'Total bobot harus 100%']);
            }
        }

        // Hapus semua criteria lama untuk periode ini, lalu insert baru
        DB::beginTransaction();
        try {
            Criteria::where('assessment_period_id', $id)->delete();
            foreach ($names as $i => $name) {
                Criteria::create([
                    'id' => Str::uuid(),
                    'assessment_period_id' => $id,
                    'name' => $name,
                    'description' => $descs[$i] ?? null,
                    'weight' => $weights[$i],
                ]);
            }
            // Jika submit, update status periode ke active
            if ($action === 'submit') {
                $periode = AssessmentPeriod::findOrFail($id);
                $periode->status = 'active';
                $periode->save();
                // Tambahkan assessment_logs
                AssessmentLog::createLog($id, 'Kriteria disubmit', 'Kriteria berhasil disubmit dan periode diaktifkan!', Auth::user()->id);
            } else {
                AssessmentLog::createLog($id, 'Kriteria diupdate', 'Draft kriteria berhasil disimpan', Auth::user()->id);
            }
            DB::commit();
            return response()->json(['success' => true, 'message' => $action === 'submit' ? 'Kriteria berhasil disubmit dan periode diaktifkan!' : 'Draft kriteria berhasil disimpan']);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }
}

