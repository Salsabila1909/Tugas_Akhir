<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fingerprint', function (Blueprint $table) {
            $table->id();

            // relasi ke siswa
            $table->unsignedBigInteger('siswa_id');

            // id fingerprint dari sensor (slot / template ID)
            $table->integer('finger_id');

            $table->timestamps();

            // foreign key
            $table->foreign('siswa_id')
                  ->references('id')
                  ->on('siswa')
                  ->onDelete('cascade');

            // biar tidak double fingerprint per siswa
            $table->unique(['siswa_id', 'finger_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fingerprint');
    }
};