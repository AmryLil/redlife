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
            $table->text('address');
            $table->string('url_address');
            $table->string('cover');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_locations');
    }
};
