<?php

use App\Models\Product;
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
        Schema::create('ram', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer',50);
            $table->string('type',10);
            $table->integer('memory');
            $table->foreignIdFor(Product::class,'product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ram');
    }
};
