<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('outlets')->whereIn('status', ['Beroperasi', 'Active'])->update(['status' => 'Aktif']);
        DB::table('outlets')->whereIn('status', ['Tidak Beroperasi', 'Nonaktif', 'Inactive'])->update(['status' => 'Tidak Aktif']);
        DB::table('riders')->whereIn('account_status', ['Nonaktif', 'Inactive'])->update(['account_status' => 'Tidak Aktif']);

        Schema::table('menus', function (Blueprint $table) {
            $table->unique('name', 'menus_name_unique');
            $table->index(['category', 'status'], 'menus_category_status_index');
        });

        Schema::table('outlets', function (Blueprint $table) {
            $table->unique('branch', 'outlets_branch_unique');
            $table->index('status', 'outlets_status_index');
        });

        Schema::table('riders', function (Blueprint $table) {
            $table->unique('outlet_id', 'riders_outlet_id_unique');
            $table->index(['account_status', 'operational_status'], 'riders_status_index');
        });

        Schema::table('stocks', function (Blueprint $table) {
            $table->unique(['outlet_id', 'menu_id'], 'stocks_outlet_menu_unique');
            $table->index(['outlet_id', 'stock_status'], 'stocks_outlet_status_index');
        });

        Schema::table('feedback', function (Blueprint $table) {
            $table->index(['status', 'feedback_date'], 'feedback_status_date_index');
        });
    }

    public function down(): void
    {
        Schema::table('feedback', function (Blueprint $table) {
            $table->dropIndex('feedback_status_date_index');
        });
        Schema::table('stocks', function (Blueprint $table) {
            $table->dropIndex('stocks_outlet_status_index');
            $table->dropUnique('stocks_outlet_menu_unique');
        });
        Schema::table('riders', function (Blueprint $table) {
            $table->dropIndex('riders_status_index');
            $table->dropUnique('riders_outlet_id_unique');
        });
        Schema::table('outlets', function (Blueprint $table) {
            $table->dropIndex('outlets_status_index');
            $table->dropUnique('outlets_branch_unique');
        });
        Schema::table('menus', function (Blueprint $table) {
            $table->dropIndex('menus_category_status_index');
            $table->dropUnique('menus_name_unique');
        });
    }
};
