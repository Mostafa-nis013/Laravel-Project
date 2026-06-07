<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ──────────────────────────────────────────────────────────────
        $superAdmin = User::create([
            'name'     => 'Super Admin',
            'email'    => 'superadmin@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
        ]);

        $admin = User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $editor = User::create([
            'name'     => 'Editor User',
            'email'    => 'editor@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'editor',
        ]);

        $user = User::create([
            'name'     => 'Regular User',
            'email'    => 'user@demo.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
        ]);

        // ── Categories ─────────────────────────────────────────────────────────
        $categories = [
            ['name' => 'Technology',  'color' => '#6366f1', 'description' => 'Tech news and tutorials'],
            ['name' => 'Design',      'color' => '#ec4899', 'description' => 'UI/UX and visual design'],
            ['name' => 'Business',    'color' => '#f59e0b', 'description' => 'Business strategy and growth'],
            ['name' => 'Science',     'color' => '#10b981', 'description' => 'Scientific discoveries'],
        ];

        foreach ($categories as $cat) {
            Category::create([
                ...$cat,
                'slug'       => \Illuminate\Support\Str::slug($cat['name']),
                'created_by' => $admin->id,
            ]);
        }

        // ── Articles ───────────────────────────────────────────────────────────
        $sampleArticles = [
            ['title' => 'Getting Started with Laravel 11', 'status' => 'published', 'author_id' => $editor->id, 'category_id' => 1],
            ['title' => 'The Future of AI in Web Development', 'status' => 'published', 'author_id' => $admin->id, 'category_id' => 1],
            ['title' => 'Modern CSS Techniques for 2025', 'status' => 'draft', 'author_id' => $editor->id, 'category_id' => 2],
            ['title' => 'Building Scalable SaaS Products', 'status' => 'published', 'author_id' => $admin->id, 'category_id' => 3],
            ['title' => 'Deep Dive: Quantum Computing Basics', 'status' => 'archived', 'author_id' => $superAdmin->id, 'category_id' => 4],
            ['title' => 'UI Design Trends to Watch', 'status' => 'draft', 'author_id' => $editor->id, 'category_id' => 2],
        ];

        foreach ($sampleArticles as $art) {
            Article::create([
                'title'        => $art['title'],
                'slug'         => \Illuminate\Support\Str::slug($art['title']),
                'excerpt'      => 'A compelling summary of ' . strtolower($art['title']) . '.',
                'content'      => '<p>This is the full content of the article. It covers everything you need to know about ' . strtolower($art['title']) . '.</p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>',
                'status'       => $art['status'],
                'author_id'    => $art['author_id'],
                'category_id'  => $art['category_id'],
                'views'        => rand(50, 800),
                'published_at' => $art['status'] === 'published' ? now()->subDays(rand(1, 30)) : null,
            ]);
        }
    }
}
