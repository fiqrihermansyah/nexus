<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'division', 'job_name', 'request_subject', 'delivery_type',
        'email_pic', 'cc_req', 'file_table_name', 'schedule',
        'jam', 'day', 'pic_dtm', 'query', 'status',
        'is_monthly', 'is_weekly', 'is_daily', 'created_by',
    ];

    protected $casts = [
        'is_monthly' => 'boolean',
        'is_weekly'  => 'boolean',
        'is_daily'   => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'Active'   => 'badge-done',
            'Inactive' => 'badge-discard',
            'Pending'  => 'badge-pending',
            default    => 'badge-pending',
        };
    }

    public function getFrequencyLabels(): array
    {
        $labels = [];
        if ($this->is_daily)   $labels[] = 'Daily';
        if ($this->is_weekly)  $labels[] = 'Weekly';
        if ($this->is_monthly) $labels[] = 'Monthly';
        return $labels;
    }

    public static function getStatusCounts(): array
    {
        return [
            'total'    => self::count(),
            'active'   => self::where('status', 'Active')->count(),
            'inactive' => self::where('status', 'Inactive')->count(),
            'pending'  => self::where('status', 'Pending')->count(),
            'daily'    => self::where('is_daily', true)->count(),
            'weekly'   => self::where('is_weekly', true)->count(),
            'monthly'  => self::where('is_monthly', true)->count(),
        ];
    }
}
