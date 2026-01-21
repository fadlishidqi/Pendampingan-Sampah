<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('map_points', function (Blueprint $table) {
            $table->id();

            // Opsional kalau kamu mau kaitkan titik ke household (misal percontohan Wiroditan)
            $table->foreignId('household_id')
                ->nullable()
                ->constrained('households')
                ->nullOnDelete();

            $table->string('title')->nullable();          // contoh: "Titik Blueprint"
            $table->string('note')->nullable();           // catatan lokasi
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 10, 7);

            $table->string('status')->default('active');  // active/inactive (optional)

            $table->timestamps();

            $table->index(['household_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('map_points');
    }
};
