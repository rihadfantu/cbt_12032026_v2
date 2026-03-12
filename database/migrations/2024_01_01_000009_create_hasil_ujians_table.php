<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hasil_ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ruang_id')->constrained('ruang_ujians')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->enum('status', ['sedang', 'selesai'])->default('sedang');
            $table->integer('sisa_waktu')->default(0);
            $table->integer('benar')->default(0);
            $table->integer('salah')->default(0);
            $table->decimal('nilai', 5, 2)->default(0);
            $table->json('answers')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hasil_ujians'); }
};
