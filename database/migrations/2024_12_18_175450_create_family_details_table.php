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
        Schema::create('family_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Foreign key to link to user
            $table->string('family_name');
            $table->integer('age')->nullable(); // Age of the family member
            $table->string('relationship')->nullable(); // Relationship (e.g., spouse, child, etc.)
            $table->string('spouse_name')->nullable(); // Spouse name
            $table->json('children')->nullable(); // Store children names as a JSON object
            $table->decimal('salary', 10, 2); // Salary field
            $table->timestamps(); // Created and updated timestamps

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('family_details');
    }
};
