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
        Schema::create('log_activities', function (Blueprint $table) {
            $table->id();
            $table->string('namaDB')->nullable();
            $table->string('ipHost')->nullable();
            $table->string('port')->nullable();
            $table->string('driver')->nullable();
            $table->longText('queryRequest')->nullable();
            $table->longText('queryResult')->nullable();
            $table->longText('deskripsi')->nullable();
            $table->longText('reason')->nullable();
            $table->string('performedBy')->nullable();
            $table->string('role')->nullable();
            $table->string('statusApproval')->nullable();
            $table->string('menu')->nullable();
            $table->string('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_activities');
    }
};
