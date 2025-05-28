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
        Schema::create('product', function (Blueprint $table) {
            $table->string('prod_code')->primary();
            $table->string('prod_name');
            $table->decimal('prod_price',16,2)->default(0);
            $table->enum('prod_status',['active','inactive'])->default('active');
            $table->string('prod_description');
            $table->string('creator');
            $table->string('editor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
