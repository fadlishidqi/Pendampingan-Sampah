<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();

            // Data inti rumah / KK
            $table->string('head_name');          // nama penanggung jawab
            $table->string('phone')->nullable();

            // Lokasi fleksibel (RT/RW boleh kosong)
            $table->string('dusun')->nullable();
            $table->string('rt')->nullable();
            $table->string('rw')->nullable();
            $table->text('address_text')->nullable();

            // Koordinat (boleh kosong dulu)
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();

            $table->unsignedSmallInteger('members_count')->default(1);

            $table->timestamps();

            $table->index(['dusun', 'rt', 'rw']);
            $table->index(['head_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('households');
    }
};
