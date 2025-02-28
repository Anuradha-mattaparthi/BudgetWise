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
        Schema::create('income', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('family_detail_id'); // Foreign key to link to family details
            $table->string('source'); // Name of the expense
            $table->decimal('amount', 10, 2); // Amount spent for the expense
            $table->timestamps(); // Created and updated timestamps

            $table->foreign('family_detail_id')->references('id')->on('family_details')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('income');
    }
};
