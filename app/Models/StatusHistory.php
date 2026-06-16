<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['report_id', 'changed_by', 'old_status', 'new_status', 'remarks'])]
class StatusHistory extends Model
{
    protected $table = 'status_history';

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function changedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
