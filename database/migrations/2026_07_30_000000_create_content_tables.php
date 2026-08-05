<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Repeating homepage content that used to live as hard-coded PHP arrays inside
 * resources/views/home/index.blade.php and layouts/app.blade.php.
 *
 * Everything here is editable from Admin → Site Content.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Career timeline.
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('company');
            $table->string('period');
            $table->string('duration')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_current')->default(false);
            $table->text('bullets')->nullable();   // one bullet per line
            $table->string('tags')->nullable();    // comma separated
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // "Technical Skills" cards.
        Schema::create('skill_groups', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fas fa-code');
            $table->string('title');
            $table->text('skills')->nullable();    // comma separated pills
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // tech-stack.json card in the About section.
        Schema::create('tech_items', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fas fa-code');
            $table->string('label');
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // The four icon rows under the About copy.
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fas fa-star');
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Hero counters, reused by the About summary row.
        Schema::create('hero_stats', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('label');
            $table->string('short_label')->nullable(); // compact form for the About row
            $table->integer('sort_order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Header / mobile / footer navigation.
        Schema::create('nav_links', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('url');                 // "/", "#about", "/blog", full URL
            $table->integer('sort_order')->default(0);
            $table->boolean('in_header')->default(true);
            $table->boolean('in_footer')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // Contact cards + footer "Connect" list + social buttons.
        Schema::create('social_links', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->default('fas fa-link');
            $table->string('label');               // "Email"
            $table->string('value')->nullable();   // shown text
            $table->string('url');                 // mailto:, https://…
            $table->integer('sort_order')->default(0);
            $table->boolean('in_contact')->default(true);
            $table->boolean('in_footer')->default(true);
            $table->boolean('is_social_btn')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('social_links');
        Schema::dropIfExists('nav_links');
        Schema::dropIfExists('hero_stats');
        Schema::dropIfExists('highlights');
        Schema::dropIfExists('tech_items');
        Schema::dropIfExists('skill_groups');
        Schema::dropIfExists('experiences');
    }
};
