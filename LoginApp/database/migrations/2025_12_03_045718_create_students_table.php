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
        Schema::create('student', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // login user
            $table->unsignedBigInteger('teacher_id'); // assigned teacher (user)

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('Name');
            $table->string('Class');
            $table->string('State');
            $table->string('PhoneNumber');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student');
    }
};
