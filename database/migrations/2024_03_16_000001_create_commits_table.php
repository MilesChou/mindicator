<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('commits', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('repository_id');
            $table->string('sha1')->unique();
            $table->json('labels')->default('[]');

            $table->foreign('repository_id')
                ->references('id')
                ->on('repositories');

            $table->index(['repository_id', 'sha1']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('commits');
    }
};
