<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloodStockComponentTable extends Migration
{
    public function up()
    {
        Schema::create('blood_stock_component', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_stock_id')->constrained('blood_stocks')->onDelete('cascade');
            $table->foreignId('blood_component_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('blood_stock_component');
    }
}
