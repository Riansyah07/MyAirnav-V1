<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void
    {
        Schema::create('locas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama loca
            $table->enum('category', ['Internal', 'Eksternal']); // Kategori
            $table->text('note')->nullable(); // Deskripsi
            $table->string('file_path');
            $table->string('file_type'); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relasi ke users
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locas');
    }
};
