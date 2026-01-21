<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('biopori_sites', function (Blueprint $table) {
            if (!Schema::hasColumn('biopori_sites', 'seq')) {
                $table->unsignedInteger('seq')->nullable()->index();
            }
        });

        Schema::table('furnace_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('furnace_plans', 'seq')) {
                $table->unsignedInteger('seq')->nullable()->index();
            }
        });
    }

    public function down(): void
    {
        Schema::table('biopori_sites', function (Blueprint $table) {
            if (Schema::hasColumn('biopori_sites', 'seq')) {
                $table->dropColumn('seq');
            }
        });

        Schema::table('furnace_plans', function (Blueprint $table) {
            if (Schema::hasColumn('furnace_plans', 'seq')) {
                $table->dropColumn('seq');
            }
        });
    }
};
