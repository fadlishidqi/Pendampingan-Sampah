<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('dashboard_contents', function (Blueprint $table) {
            $table->id();
            $table->string('hero_title')->nullable();
            $table->text('hero_desc')->nullable();

            // edukasi: array of cards [{title, body}, ...]
            $table->json('edukasi_cards')->nullable();

            // dokumentasi: array of cards [{title, body, image}, ...]
            $table->json('dokumentasi_cards')->nullable();

            // tentang: ringkasan + kontak
            $table->text('tentang_desc')->nullable();
            $table->string('kontak')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dashboard_contents');
    }
};
