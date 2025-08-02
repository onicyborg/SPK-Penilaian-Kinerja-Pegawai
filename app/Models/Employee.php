<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'employees';
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
        'position',
        'department',
        'hire_date',
        'phone',
        'email',
        'status',
        'photo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'hire_date' => 'date',
    ];

    /**
     * Get employee assessments for this employee
     */
    public function employeeAssessments(): HasMany
    {
        return $this->hasMany(EmployeeAssessment::class, 'employee_id');
    }

    /**
     * Get SAW results for this employee
     */
    public function sawResults(): HasMany
    {
        return $this->hasMany(SawResult::class, 'employee_id');
    }

    /**
     * Get assessments for a specific period
     */
    public function assessmentsForPeriod($periodId): HasMany
    {
        return $this->employeeAssessments()->where('assessment_period_id', $periodId);
    }

    /**
     * Get SAW result for a specific period
     */
    public function sawResultForPeriod($periodId)
    {
        return $this->sawResults()->where('assessment_period_id', $periodId)->first();
    }

    /**
     * Check if employee is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
}
