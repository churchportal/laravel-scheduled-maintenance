<?php

namespace Churchportal\ScheduledMaintenance\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class ScheduledMaintenanceModel extends Model implements ScheduledMaintenanceInterface
{
    use SoftDeletes;

    protected $guarded = [
        'id',
    ];

    public function getTable()
    {
        return config('scheduled-maintenance.table_name');
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->uuid = (app(\Faker\Provider\Uuid::class))::uuid();
        });
    }

    protected $dates = [
        'display_notice_at',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'is_active' => 'bool',
        'settings' => 'array',
    ];

    public function redirectTo(): ?string
    {
        return Arr::get($this->settings, 'redirect_to', config('scheduled-maintenance.redirect_to'));
    }

    public function statusCode(): ?int
    {
        return (int) Arr::get($this->settings, 'status_code', config('scheduled-maintenance.status_code'));
    }

    public function bypassSecret(): ?string
    {
        return Arr::get($this->settings, 'bypass_secret', config('scheduled-maintenance.bypass_secret'));
    }
}
