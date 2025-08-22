<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ref_harga_paket', function (Blueprint $table) {
            $table->id('log_key'); // Auto-increment primary key
            $table->string('alias_paket', 150);
            $table->char('paket', 10);
            $table->char('ref_gol', 10);
            $table->decimal('dpp', 65, 2)->nullable();
            $table->decimal('ppn', 65, 2)->nullable();
            $table->decimal('total_amount', 65, 2)->nullable();
            $table->decimal('margin', 65, 2)->nullable();
            $table->string('status')->nullable();
            $table->datetime('create_log')->nullable();
            $table->string('jenis_paket', 100)->nullable();
        });
    }

    public function down(): void {
        Schema::dropIfExists('ref_harga_paket');
    }
};
