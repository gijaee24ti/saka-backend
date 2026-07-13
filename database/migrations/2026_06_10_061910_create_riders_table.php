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
    Schema::create('riders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('outlet_id')->nullable()->constrained('outlets')->nullOnDelete();
        $table->string('name');
        $table->string('username')->unique();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->string('account_status')->default('Aktif');
        $table->string('operational_status')->default('Tutup');
        $table->text('note')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
