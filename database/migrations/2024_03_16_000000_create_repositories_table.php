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
            $table->integer('owner_id');
            $table->string('url');
            $table->string('private_key');
            $table->string('tags');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('repositories');
    }
};
