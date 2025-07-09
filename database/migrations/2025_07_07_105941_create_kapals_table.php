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
        Schema::create('kapals', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kapal');
            $table->string('tipe');
            $table->integer('kapasitas');
            $table->string('kode_kapal')->nullable();
            $table->text('rute');
            $table->string('home_base');
            $table->enum('status', ['aktif', 'tidak aktif', 'perbaikan', 'ditahan'])->default('aktif');
            $table->string('operator')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kapals');
    }
};
