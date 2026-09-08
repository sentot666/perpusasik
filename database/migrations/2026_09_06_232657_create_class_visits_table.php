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
        Schema::create('class_visits', function (Blueprint $table) {
            $table->id();
            $table->enum('level', ['sd', 'smp', 'sma']);
            $table->string('day');
            $table->date('date')->nullable();
            $table->string('time');
            $table->string('class_name');
            $table->string('teacher_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_visits');
    }
};
