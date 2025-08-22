<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id(); // Primary Key 'id'

            $table->string('kode_pelanggan', 100)->unique();
            $table->string('nama_pelanggan', 100);
            $table->string('password_pelanggan', 100)->nullable();
            $table->string('alamat_pelanggan', 200)->nullable();
            $table->string('rt', 10)->nullable();
            $table->string('rw', 10)->nullable();
            $table->string('kelurahan_id', 150)->nullable();
            $table->string('kecamatan', 150)->nullable();
            $table->string('telp_user', 100)->nullable();
            $table->string('id_telegram', 100)->nullable();
            $table->string('status_log', 255)->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->string('status_followup', 100)->nullable();
            $table->string('stts_send_survei', 255)->nullable();
            $table->timestamp('log_aktivasi')->nullable();
            $table->string('va_bri', 150)->nullable();
            $table->string('va_bca', 150)->nullable();

            // Relasi ke microservice lain (tanpa foreign key)
            $table->unsignedBigInteger('unit_id')->nullable(); // hanya menyimpan ID dari unit-service
            $table->unsignedBigInteger('harga_paket_id')->nullable(); // dari harga-paket-service

            $table->string('no_combo', 100)->nullable();
            $table->string('log_username_dcp', 200)->nullable();
            $table->string('pendaftaran_id', 100)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pelanggan');
    }
};
