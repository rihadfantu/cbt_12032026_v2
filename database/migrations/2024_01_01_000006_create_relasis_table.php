<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('relasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->json('kelas_ids')->nullable();
            $table->json('mapel_ids')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('relasis'); }
};
