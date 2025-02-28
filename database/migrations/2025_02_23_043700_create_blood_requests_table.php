<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('user_id')->nullable();  // Jika request dibuat oleh keluarga pasien
            $table->unsignedBigInteger('hospital_id');  // Rumah sakit yang membutuhkan darah
            $table->string('blood_type');
            $table->integer('quantity');  // Jumlah kantong darah yang dibutuhkan
            $table->string('status');  // Status request

            $table->dateTime('needed_by')->nullable();  // Kapan darah dibutuhkan
            $table->text('description')->nullable();  // Keterangan tambahan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blood_requests');
    }
};
