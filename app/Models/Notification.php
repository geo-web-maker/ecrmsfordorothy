<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'report_id',
    'recipient_id',
    'recipient_contact',
    'channel',
    'message',
    'status',
])]
class Notification extends Model
{
    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
