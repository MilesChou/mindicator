<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('repositories', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('url')->unique();
            $table->string('private_key')->nullable();
            $table->json('tags')->default('[]');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
