<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('esp_scan', function (Blueprint $table) {

            $table->id();

            $table->foreignId('produk_id')
                  ->nullable()
                  ->constrained('produk')
                  ->onDelete('cascade');

            $table->string('kode_barang');

            $table->timestamp('waktu_scan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('esp_scan');
    }
};