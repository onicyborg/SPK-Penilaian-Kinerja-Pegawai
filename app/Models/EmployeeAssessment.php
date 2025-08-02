<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAssessment extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employee_assessments';
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
        'criteria_id',
        'score',
        'notes',
        'assessed_by',
        'assessed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assessed_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * Get the assessment period this assessment belongs to
     */
    public function assessmentPeriod(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'assessment_period_id');
    }

    /**
     * Get the employee being assessed
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    /**
     * Get the criteria being assessed
     */
    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'criteria_id');
    }

    /**
     * Get the user who conducted the assessment
     */
    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }

    /**
     * Check if score is valid (1-5)
     */
    public function isValidScore(): bool
    {
        return $this->score >= 1 && $this->score <= 5;
    }

    /**
     * Get normalized score (for SAW calculation)
     */
    public function getNormalizedScore(): float
    {
        // For benefit criteria: score / max_score
        // Since all scores are 1-5, max is always 5
        return $this->score / 5;
    }

    /**
     * Get score as percentage
     */
    public function getScorePercentage(): float
    {
        return ($this->score / 5) * 100;
    }
}
