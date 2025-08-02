<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'criteria';
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
        'name',
        'description',
        'weight',
    ];

    /**
     * Get the assessment period this criteria belongs to
     */
    public function assessmentPeriod(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'assessment_period_id');
    }

    /**
     * Get employee assessments for this criteria
     */
    public function employeeAssessments(): HasMany
    {
        return $this->hasMany(EmployeeAssessment::class, 'criteria_id');
    }

    /**
     * Get assessments for this criteria in a specific period
     */
    public function assessmentsInPeriod($periodId): HasMany
    {
        return $this->employeeAssessments()->where('assessment_period_id', $periodId);
    }

    /**
     * Get average score for this criteria
     */
    public function getAverageScore(): float
    {
        return $this->employeeAssessments()->avg('score') ?? 0;
    }

    /**
     * Get maximum score for this criteria (always 5 based on requirements)
     */
    public function getMaxScore(): int
    {
        return 5;
    }

    /**
     * Get minimum score for this criteria (always 1 based on requirements)
     */
    public function getMinScore(): int
    {
        return 1;
    }
}
