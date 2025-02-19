<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('golongan_darah');
            $table->string('rhesus');
            $table->integer('usia');
            $table->integer('berat_badan');
            $table->text('riwayat_penyakit')->nullable();
            $table->enum('status_kelayakan', ['layak', 'tidak'])->default('layak');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
