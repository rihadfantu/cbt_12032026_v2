<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pengumuman', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->json('target_kelas')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pengumuman'); }
};
