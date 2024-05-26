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
        Schema::table('spends', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->after('amount')->nullable();
            $table->foreign('user_id') //ini hasil kolom relasinya
                    ->references('id')
                    ->on('users') //ini dari tabel users
                    ->onDelete('restrict')
                    ->onUpdate('cascade');   
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spends', function (Blueprint $table) {
            //
        });
    }
};
