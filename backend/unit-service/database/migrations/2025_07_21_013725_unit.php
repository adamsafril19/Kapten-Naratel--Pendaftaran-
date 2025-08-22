<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('units', function (Blueprint $table) {
            $table->id(); // integer auto-increment primary key
            $table->string('nama_unit', 255);
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down(): void {
    /**
     * Reverse the migrations.
     *
     * @return void
     */
        Schema::dropIfExists('units');
    }
};
