<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SawResult extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'saw_results';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'assessment_period_id',
        'employee_id',
        'normalized_scores',
        'weighted_scores',
        'final_score',
        'rank',
        'calculation_details',
        'calculated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'normalized_scores' => 'array',
        'weighted_scores' => 'array',
        'calculation_details' => 'array',
        'calculated_at' => 'datetime',
        'final_score' => 'decimal:4',
    ];

    /**
     * Get the assessment period this result belongs to
     */
    public function assessmentPeriod(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'assessment_period_id');
    }

    /**
     * Get the employee this result belongs to
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get final score as percentage
     */
    public function getFinalScorePercentage(): float
    {
        return $this->final_score * 100;
    }

    /**
     * Get rank suffix (1st, 2nd, 3rd, etc.)
     */
    public function getRankWithSuffix(): string
    {
        $rank = $this->rank;

        if ($rank % 100 >= 11 && $rank % 100 <= 13) {
            return $rank . 'th';
        }

        switch ($rank % 10) {
            case 1: return $rank . 'st';
            case 2: return $rank . 'nd';
            case 3: return $rank . 'rd';
            default: return $rank . 'th';
        }
    }

    /**
     * Get performance category based on final score
     */
    public function getPerformanceCategory(): string
    {
        $score = $this->final_score;

        if ($score >= 0.9) return 'Excellent';
        if ($score >= 0.8) return 'Very Good';
        if ($score >= 0.7) return 'Good';
        if ($score >= 0.6) return 'Fair';
        return 'Poor';
    }

    /**
     * Get performance category color for UI
     */
    public function getPerformanceCategoryColor(): string
    {
        $category = $this->getPerformanceCategory();

        return match($category) {
            'Excellent' => 'success',
            'Very Good' => 'info',
            'Good' => 'primary',
            'Fair' => 'warning',
            'Poor' => 'danger',
            default => 'secondary'
        };
    }
}
