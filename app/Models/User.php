<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'is_active', 'last_login_at',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at'     => 'datetime',
        'is_active'         => 'boolean',
        'password'          => 'hashed',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────
    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id');
    }

    // ── Role helpers ───────────────────────────────────────────────────────────
    public function isSuperAdmin(): bool { return $this->role === 'super_admin'; }
    public function isAdmin(): bool      { return $this->role === 'admin'; }
    public function isEditor(): bool     { return $this->role === 'editor'; }
    public function isUser(): bool       { return $this->role === 'user'; }

    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles);
    }

    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->getPermissions());
    }

    public function getPermissions(): array
    {
        return match ($this->role) {
            'super_admin' => [
                'manage_users', 'manage_roles',
                'create_articles', 'edit_any_article', 'delete_any_article', 'publish_articles',
                'manage_categories', 'view_dashboard', 'view_reports',
            ],
            'admin' => [
                'manage_users',
                'create_articles', 'edit_any_article', 'delete_any_article', 'publish_articles',
                'manage_categories', 'view_dashboard', 'view_reports',
            ],
            'editor' => [
                'create_articles', 'edit_own_article', 'delete_own_article', 'publish_articles',
                'manage_categories', 'view_dashboard',
            ],
            'user' => [
                'view_dashboard',
            ],
            default => [],
        };
    }

    public function getRoleBadgeColor(): string
    {
        return match ($this->role) {
            'super_admin' => 'badge-super-admin',
            'admin'       => 'badge-admin',
            'editor'      => 'badge-editor',
            default       => 'badge-user',
        };
    }

    public function getRoleLabel(): string
    {
        return match ($this->role) {
            'super_admin' => 'Super Admin',
            'admin'       => 'Admin',
            'editor'      => 'Editor',
            default       => 'User',
        };
    }
}
