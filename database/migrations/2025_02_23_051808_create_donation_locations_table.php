<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('donation_locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name');
            $table->string('location_detail');
            $table->string('city');
            $table->text('address');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('url_address');
            $table->string('cover');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_locations');
    }
};
