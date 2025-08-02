<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentLog extends Model
{
    use HasFactory, HasUuids;
    protected $table = 'assessment_logs';
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
        'action',
        'description',
        'user_id',
    ];

    /**
     * Get the assessment period this log belongs to
     */
    public function assessmentPeriod(): BelongsTo
    {
        return $this->belongsTo(AssessmentPeriod::class, 'assessment_period_id');
    }

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get action badge color for UI
     */
    public function getActionBadgeColor(): string
    {
        return match($this->action) {
            'created' => 'success',
            'updated' => 'info',
            'calculated' => 'primary',
            'completed' => 'warning',
            'deleted' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get formatted action text
     */
    public function getFormattedAction(): string
    {
        return ucfirst(str_replace('_', ' ', $this->action));
    }

    /**
     * Create a log entry
     */
    public static function createLog(string $periodId, string $action, string $description, string $userId): self
    {
        return self::create([
            'assessment_period_id' => $periodId,
            'action' => $action,
            'description' => $description,
            'user_id' => $userId,
        ]);
    }
}
