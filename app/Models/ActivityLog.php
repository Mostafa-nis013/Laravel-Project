<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id', 'action', 'model_type', 'model_id',
        'model_label', 'description', 'changes', 'ip_address', 'user_agent',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Action colour for badges ──────────────────────────────────────────────
    public function getActionColorAttribute(): string
    {
        return match ($this->action) {
            'created'  => 'green',
            'updated'  => 'blue',
            'deleted'  => 'red',
            'restored' => 'yellow',
            'login'    => 'purple',
            'logout'   => 'gray',
            'status'   => 'blue',
            default    => 'gray',
        };
    }

    // ── Static logger helper ──────────────────────────────────────────────────
    public static function log(
        string  $action,
        string  $description,
        ?Model  $model = null,
        ?array  $changes = null,
    ): self {
        return static::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => $model ? get_class($model) : null,
            'model_id'    => $model?->getKey(),
            'model_label' => $model?->name ?? $model?->order_number ?? null,
            'description' => $description,
            'changes'     => $changes,
            'ip_address'  => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
