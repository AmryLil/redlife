<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_type_id')->constrained('blood_types')->onDelete('cascade');
            $table->foreignId('storage_location_id')->constrained('storage_locations')->onDelete('cascade');
            $table->integer('quantity')->default(0);
            $table->date('collection_date');  // Tanggal darah diambil
            $table->date('expiry_date');
            $table->foreignId('donation_id')->nullable()->constrained('donations')->onDelete('set null');
            $table->enum('status', ['available', 'used', 'expired'])->default('available');
            $table->enum('blood_component', ['whole_blood', 'plasma', 'platelets', 'red_blood_cells']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_stocks');
    }
};
