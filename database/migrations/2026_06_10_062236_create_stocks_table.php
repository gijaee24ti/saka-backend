<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->foreignId('outlet_id')->constrained('outlets')->cascadeOnDelete();
        $table->foreignId('menu_id')->constrained('menus')->cascadeOnDelete();
        $table->foreignId('rider_id')->nullable()->constrained('riders')->nullOnDelete();
        $table->string('stock_status')->default('Tersedia');
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
