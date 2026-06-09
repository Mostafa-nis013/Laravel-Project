<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;

    // The four built-in role names — use these constants everywhere
    // to avoid magic strings scattered through the codebase.
    const ADMIN     = 'admin';
    const EDITOR    = 'editor';
    const MODERATOR = 'moderator';
    const USER      = 'user';

    const ALL = [
        self::ADMIN,
        self::EDITOR,
        self::MODERATOR,
        self::USER,
    ];

    protected $fillable = ['name', 'label', 'description'];

    // ── Relationships ────────────────────────────────────────────────────────

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user')->withTimestamps();
    }

    // ── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Find a role by name or throw a clean exception.
     */
    public static function findByName(string $name): self
    {
        return static::where('name', $name)->firstOrFail();
    }
}
