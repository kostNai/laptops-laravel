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
        Schema::create('cpu', function (Blueprint $table) {
            $table->id();
            $table->string('manufacturer',50);
            $table->string('series',50);
            $table->string('model',50);
            $table->integer('cores_value');
            $table->integer('frequency');
            $table->foreignIdFor(Product::class,'product_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cpu');
    }
};
