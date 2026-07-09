<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Write an audit transaction record entry directly into the tracking database.
     */
    public function logAction(string $action, string $targetType, string $description): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user() ? Auth::user()->name : 'System/Guest',
            'action'      => $action,
            'target_type' => $targetType,
            'description' => $description,
        ]);
    }
}