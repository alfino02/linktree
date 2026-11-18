<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('linktree_contents', function (Blueprint $table) {
            $table->id();
            $table->string('site', 32)->unique();
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('linktree_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 64)->unique();
            $table->text('value');
            $table->timestamps();
        });

        Schema::create('linktree_visits', function (Blueprint $table) {
            $table->id();
            $table->string('site', 32)->index();
            $table->string('visit_date', 10)->index();
            $table->string('time_wib', 8);
            $table->string('vid', 16)->index();
            $table->string('device', 24);
            $table->string('source', 32);
            $table->string('country', 64)->nullable();
            $table->string('city', 64)->nullable();
            $table->string('path', 120)->default('/');
            $table->unsignedBigInteger('visited_at_ms')->index();
            $table->timestamps();

            $table->index(['site', 'visit_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('linktree_visits');
        Schema::dropIfExists('linktree_settings');
        Schema::dropIfExists('linktree_contents');
    }
};
