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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image',1000)->nullable()->default(null);
            $table->string('name',100);
            $table->string('model',100);
            $table->string('color',50);
            $table->float('weight');
            $table->string('multimedia',250);
            $table->string('dimensions',20);
            $table->string('os',20);
            $table->foreignId('cpu_id')->nullable()->default(null);
            $table->foreignId('display_id')->nullable()->default(null);
            $table->foreignId('memory_id')->nullable()->default(null);
            $table->foreignId('ram_id')->nullable()->default(null);
            $table->foreignId('graphic_id')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
