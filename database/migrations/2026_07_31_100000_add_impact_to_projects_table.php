<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One measurable outcome per project — "Cut checkout time 40%",
 * "Serves 4,000+ students". Rendered as the impact line on project cards,
 * which is what turns a screenshot grid into a case-study grid.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('impact', 160)->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('impact');
        });
    }
};
