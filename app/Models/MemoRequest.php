<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'memo_number', 'request_description', 'category', 'division',
        'pic_dtm', 'received_date', 'status', 'submitted_date',
        'handover_memo_number', 'notes', 'created_by',
        'attachment_path', 'attachment_name', 'attachment_mime',
    ];

    protected $casts = [
        'received_date'  => 'date',
        'submitted_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function activities()
    {
        return $this->hasMany(MemoActivity::class)->orderBy('created_at', 'desc');
    }

    public function getStatusColorClass(): string
    {
        return match($this->status) {
            'Pending'     => 'badge-pending',
            'On Progress' => 'badge-progress',
            'Done'        => 'badge-done',
            'Discard'     => 'badge-discard',
            default       => 'badge-pending',
        };
    }

    public function isImage(): bool
    {
        return in_array($this->attachment_mime, [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'
        ]);
    }

    public function isPdf(): bool
    {
        return $this->attachment_mime === 'application/pdf';
    }

    public function hasAttachment(): bool
    {
        return !empty($this->attachment_path);
    }

    public static function getStatusCounts(): array
    {
        return [
            'total'       => self::count(),
            'pending'     => self::where('status', 'Pending')->count(),
            'on_progress' => self::where('status', 'On Progress')->count(),
            'done'        => self::where('status', 'Done')->count(),
            'discard'     => self::where('status', 'Discard')->count(),
        ];
    }
}
