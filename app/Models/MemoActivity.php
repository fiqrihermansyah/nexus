<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemoActivity extends Model
{
    protected $fillable = [
        'memo_request_id', 'user_id', 'action', 'description', 'old_status', 'new_status',
    ];

    public function memoRequest()
    {
        return $this->belongsTo(MemoRequest::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
