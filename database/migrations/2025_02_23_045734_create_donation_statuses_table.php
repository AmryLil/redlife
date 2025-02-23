<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('donation_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('status');  // pending, completed, rejected
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donation_statuses');
    }
};
