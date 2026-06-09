<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    // ── Relationships ────────────────────────────────────────────────────────

    /**
     * A user can have many roles (many-to-many).
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user')->withTimestamps();
    }

    // ── Role helpers ─────────────────────────────────────────────────────────

    /**
     * Check if the user has a specific role by name.
     *   $user->hasRole('admin')
     *   $user->hasRole(['admin', 'editor'])   ← any match = true
     */
    public function hasRole(string|array $roles): bool
    {
        $roles = (array) $roles;
        return $this->roles->pluck('name')->intersect($roles)->isNotEmpty();
    }

    /**
     * Check if the user has ALL of the given roles.
     */
    public function hasAllRoles(array $roles): bool
    {
        return collect($roles)->every(fn($r) => $this->roles->pluck('name')->contains($r));
    }

    /**
     * Shorthand helpers for each role.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isEditor(): bool
    {
        return $this->hasRole('editor');
    }

    public function isModerator(): bool
    {
        return $this->hasRole('moderator');
    }

    /**
     * Assign a role (or array of roles) to the user.
     * Accepts role names (string) or Role model instances.
     */
    public function assignRole(string|array|Role $roles): void
    {
        $roles = collect((array) $roles)->map(function ($role) {
            return $role instanceof Role ? $role : Role::where('name', $role)->firstOrFail();
        });

        $this->roles()->syncWithoutDetaching($roles->pluck('id')->toArray());
    }

    /**
     * Remove a role from the user.
     */
    public function removeRole(string|Role $role): void
    {
        $role = $role instanceof Role ? $role : Role::where('name', $role)->firstOrFail();
        $this->roles()->detach($role->id);
    }

    /**
     * Sync roles — replaces all existing roles with the given set.
     */
    public function syncRoles(array $roleNames): void
    {
        $ids = Role::whereIn('name', $roleNames)->pluck('id');
        $this->roles()->sync($ids);
    }

    /**
     * Get a comma-separated list of role labels for display.
     */
    public function getRoleLabelAttribute(): string
    {
        return $this->roles->pluck('label')->join(', ') ?: 'No role';
    }
}
