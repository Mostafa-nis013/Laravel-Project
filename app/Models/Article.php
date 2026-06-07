<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'status',
        'author_id', 'category_id', 'featured_image', 'views', 'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // ── Relationships ──────────────────────────────────────────────────────────
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // ── Scopes ─────────────────────────────────────────────────────────────────
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByAuthor($query, int $userId)
    {
        return $query->where('author_id', $userId);
    }

    // ── Helpers ────────────────────────────────────────────────────────────────
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();
        return $count ? "{$slug}-{$count}" : $slug;
    }

    public function getStatusBadgeColor(): string
    {
        return match ($this->status) {
            'published' => 'badge-published',
            'draft'     => 'badge-draft',
            default     => 'badge-archived',
        };
    }

    public function getReadingTime(): int
    {
        return (int) ceil(str_word_count(strip_tags($this->content)) / 200);
    }
}
