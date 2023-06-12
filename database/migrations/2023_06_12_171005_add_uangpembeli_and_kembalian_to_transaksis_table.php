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
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedBigInteger('kembalian')->after('total');
            $table->unsignedBigInteger('uangpembeli')->after('kembalian');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            if (Schema::hasColumn('transaksis', 'kembalian')) {
                $table->dropColumn('kembalian');
            }
            if (Schema::hasColumn('transaksis', 'uangpembeli')) {
                $table->dropColumn('uangpembeli');
            }
        });
    }
};
