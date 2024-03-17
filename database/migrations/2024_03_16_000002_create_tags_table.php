<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->integer('repository_id');
            $table->string('commit_sha1')->unique();

            $table->foreign('repository_id')
                ->references('id')
                ->on('repositories');

            $table->foreign('commit_sha1')
                ->references('sha1')
                ->on('commits');

            $table->index(['repository_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
