<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\GradingThreshold;
use Illuminate\Http\Request;

class GradingThresholdController extends Controller
{
    public function index()
    {
        $thresholds = GradingThreshold::orderBy('employee_level')
            ->orderByDesc('min_score')
            ->get()
            ->groupBy('employee_level');

        return view('master.grading.index', [
            'thresholds' => $thresholds,
        ]);
    }

    public function update(Request $request, GradingThreshold $grading)
    {
        $validated = $request->validate([
            'min_score' => ['required', 'integer', 'min:0'],
            'max_score' => ['nullable', 'integer', 'gte:min_score'],
            'reward_text' => ['nullable', 'string'],
            'punishment_text' => ['nullable', 'string'],
        ]);

        $oldData = $grading->toArray();

        $grading->update([
            'min_score' => $validated['min_score'],
            'max_score' => $validated['max_score'] ?? null,
            'reward_text' => $validated['reward_text'] ?? '-',
            'punishment_text' => $validated['punishment_text'] ?? '-',
        ]);

        ActivityLog::record('update_grading_threshold', $grading, $oldData, $grading->toArray());

        return redirect()->route('grading.index')->with('success', "Grading {$grading->grade} (Level {$grading->employee_level}) berhasil diperbarui.");
    }
}
