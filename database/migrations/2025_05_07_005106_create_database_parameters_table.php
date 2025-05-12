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
        Schema::create('database_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('namaDB');
            $table->string('ipHost');
            $table->string('port');
            $table->string('driver');
            $table->string('reason')->nullable();
            $table->integer('statusApproval')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_parameters');
    }
};
