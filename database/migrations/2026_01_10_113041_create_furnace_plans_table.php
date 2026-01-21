<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('furnace_plans', function (Blueprint $table) {
            $table->id();

            // Bisa terkait ke 1 rumah (kalau tungku ditaruh di rumah tertentu)
            $table->foreignId('household_id')
                  ->nullable()
                  ->constrained('households')
                  ->nullOnDelete();

            $table->string('title');                 // contoh: Tungku Percontohan Minim Asap
            $table->string('type')->nullable();       // drum/bata/hybrid
            $table->unsignedInteger('capacity_liter')->nullable();

            $table->text('design_summary')->nullable();
            $table->text('material_list')->nullable();
            $table->text('safety_notes')->nullable();

            // Lokasi fleksibel (kalau belum fix)
            $table->string('location_note')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->string('status')->default('draft'); // draft/review/active
            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('furnace_plans');
    }
};
