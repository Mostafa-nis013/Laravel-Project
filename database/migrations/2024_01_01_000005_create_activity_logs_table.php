<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action');           // created, updated, deleted, login, logout
            $table->string('model_type')->nullable();  // App\Models\Product
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_label')->nullable(); // "Wireless Headphones"
            $table->text('description');
            $table->json('changes')->nullable(); // before/after snapshot
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
