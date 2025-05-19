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
        Schema::create('approval_queries', function (Blueprint $table) {
            $table->id();
            $table->string('namaDB');
            $table->string('ipHost');
            $table->string('port');
            $table->string('driver');
            $table->longText('queryRequest');
            $table->longText('queryResult')->nullable();
            $table->longText('deskripsi');
            $table->string('username');
            $table->string('password');
            $table->string('reason')->nullable();
            $table->string('operator')->nullable();
            $table->string('checker')->nullable();
            $table->string('supervisor')->nullable();
            $table->integer('statusApproval');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_queries');
    }
};
