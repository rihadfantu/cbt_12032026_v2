<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('name');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->string('password');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('siswas'); }
};
