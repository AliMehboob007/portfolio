<?php
// ═══════════════════════════════════════════════════════════════
// Run: php artisan migrate
// All migrations as reference — split into separate files in real app
// ═══════════════════════════════════════════════════════════════

// database/migrations/2024_01_01_000001_create_projects_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('category');
            $table->string('project_type')->nullable();
            $table->text('description');
            $table->text('challenges')->nullable();
            $table->string('tech_stack');
            $table->string('image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('live_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('client_name')->nullable();
            $table->year('year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('status')->default('completed');
            $table->timestamps();
        });

        // ─────────────────────────────────────────────────────────────────
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt', 500);
            $table->longText('body');
            $table->string('category');
            $table->string('image')->nullable();
            $table->boolean('published')->default(false);
            $table->integer('read_time')->default(5);
            $table->timestamps();
        });

        // ─────────────────────────────────────────────────────────────────
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('project_type')->nullable();
            $table->text('message');
            $table->boolean('read')->default(false);
            $table->timestamps();
        });

        // ─────────────────────────────────────────────────────────────────
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('company');
            $table->text('message');
            $table->tinyInteger('rating')->default(5);
            $table->string('avatar')->nullable();
            $table->boolean('active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // ─────────────────────────────────────────────────────────────────
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('settings');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('contact_messages');
        Schema::dropIfExists('blog_posts');
        Schema::dropIfExists('projects');
    }
};
