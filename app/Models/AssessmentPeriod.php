<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AssessmentPeriod extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'assessment_periods';
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'status',
        'created_by',
    ];

    /**
     * Get the user who created this assessment period
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get criteria for this assessment period
     */
    public function criteria(): HasMany
    {
        return $this->hasMany(Criteria::class, 'assessment_period_id');
    }

    /**
     * Get employee assessments for this period
     */
    public function employeeAssessments(): HasMany
    {
        return $this->hasMany(EmployeeAssessment::class, 'assessment_period_id');
    }

    /**
     * Get SAW results for this period
     */
    public function sawResults(): HasMany
    {
        return $this->hasMany(SawResult::class, 'assessment_period_id');
    }

    /**
     * Get assessment logs for this period
     */
    public function assessmentLogs(): HasMany
    {
        return $this->hasMany(AssessmentLog::class, 'assessment_period_id');
    }

    /**
     * Check if period is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if period is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if period is draft
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Get total number of employees assessed in this period
     */
    public function getTotalEmployeesAssessed(): int
    {
        return $this->employeeAssessments()
            ->distinct('employee_id')
            ->count('employee_id');
    }

    /**
     * Get total number of criteria in this period
     */
    public function getTotalCriteria(): int
    {
        return $this->criteria()->count();
    }
}
