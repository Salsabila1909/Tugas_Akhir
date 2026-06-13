<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();

            /**
             * JENIS TRANSAKSI:
             * - payment = pembelian produk
             * - topup = isi saldo
             */
            $table->enum('type', ['payment', 'topup']);

            // siswa wajib ada
            $table->foreignId('siswa_id')
                ->constrained('siswa')
                ->onDelete('cascade');

            /**
             * PRODUK hanya untuk payment
             * nullable untuk topup
             */
            $table->foreignId('produk_id')
                ->nullable()
                ->constrained('produk')
                ->onDelete('cascade');

            // qty hanya untuk payment
            $table->integer('qty')->nullable();

            // snapshot harga saat transaksi (payment)
            $table->decimal('harga_satuan', 12, 2)->nullable();

            // total pembayaran / nominal topup
            $table->decimal('total', 12, 2);

            /**
             * STATUS FLOW:
             * pending → rfid_verified → finger_verified → success / failed
             */
            $table->enum('status', [
                'pending',
                'rfid_verified',
                'finger_verified',
                'success',
                'failed'
            ])->default('pending');

            /**
             * METODE AUTHENTICATION
             * bisa salah satu atau dua tahap
             */
            $table->enum('metode', ['rfid', 'fingerprint'])->nullable();

            /**
             * SNAPSHOT AUTH (untuk audit & history)
             */
            $table->string('rfid_uid')->nullable();
            $table->integer('finger_id')->nullable();

            // waktu pembayaran selesai
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};