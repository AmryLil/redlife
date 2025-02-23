<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('donation_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade'); // Relasi ke rumah sakit/bank darah
            $table->foreignId('donation_location_id')->constrained()->onDelete('cascade'); // Relasi ke lokasi donor
            $table->date('date'); // Tanggal donor
            $table->time('time'); // Jam donor
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_schedules');
    }
};
