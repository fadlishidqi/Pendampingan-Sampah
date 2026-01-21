<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('biopori_sites', function (Blueprint $table) {
            $table->id();

            // Relasi ke rumah/KK (boleh null kalau belum jelas)
            $table->foreignId('household_id')
                  ->nullable()
                  ->constrained('households')
                  ->nullOnDelete();

            // Identitas titik biopori
            $table->string('code')->unique(); // contoh: BIO-001
            $table->string('location_note')->nullable(); 
            // contoh: "belakang rumah dekat pohon mangga"

            // Spesifikasi (boleh kosong dulu)
            $table->unsignedSmallInteger('diameter_cm')->nullable();
            $table->unsignedSmallInteger('depth_cm')->nullable();

            // Koordinat (opsional, kalau nanti sudah tahu)
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            // Status titik biopori
            $table->string('status')->default('planned');
            // planned | active | maintenance | inactive

            $table->date('installed_at')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biopori_sites');
    }
};
