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
        Schema::create('jadwal_keberangkatans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kapal_id')->constrained('kapals')->onDelete('cascade');
            $table->date('tanggal_berangkat');
            $table->time('jam_berangkat');
            $table->string('tujuan');
            $table->enum('status', ['tersedia', 'selesai', 'dibatalkan'])->default('tersedia');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_keberangkatans');
    }
};
