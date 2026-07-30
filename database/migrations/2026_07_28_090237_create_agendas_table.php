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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->string('category', 50)->default('Lainnya');
            $table->text('description')->nullable();
            $table->date('event_date');
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->nullable();
            $table->string('location', 255)->default('Ruang Baca Utama');
            $table->string('speaker', 255)->nullable();
            $table->string('target_audience', 255)->nullable()->default('Umum');
            $table->integer('quota')->nullable();
            $table->string('poster_image', 255)->nullable();
            $table->string('status', 30)->default('Akan Datang');
            $table->boolean('is_published')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
