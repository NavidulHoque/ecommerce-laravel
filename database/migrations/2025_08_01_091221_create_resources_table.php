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
        Schema::create('resources', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discount_price', 10, 2)->default(0);
            $table->decimal('resultant_price', 10, 2)->nullable();
            $table->integer('quantity');
            $table->string('format_url');
            $table->string('format_type');
            $table->foreignId('category_id')->constrained("categories")->onDelete('cascade');
            $table->foreignId('sub_category_id')->constrained("sub_categories")->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('product_id')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
