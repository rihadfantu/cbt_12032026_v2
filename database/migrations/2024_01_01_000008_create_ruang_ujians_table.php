<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('ruang_ujians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('token', 10)->unique();
            $table->foreignId('bank_id')->constrained('bank_soals')->onDelete('cascade');
            $table->integer('login_limit')->default(3);
            $table->integer('min_time_submit')->default(0);
            $table->json('classes')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->boolean('random_soal')->default(false);
            $table->boolean('random_ops')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('ruang_ujians'); }
};
