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
    Schema::create('outlets', function (Blueprint $table) {
        $table->id();
        $table->string('branch');
        $table->string('vehicle')->nullable();
        $table->time('open_time')->nullable();
        $table->time('close_time')->nullable();
        $table->string('status')->default('Aktif');
        $table->text('address')->nullable();
        $table->text('maps_link')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outlets');
    }
};
