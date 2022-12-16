<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->string('driverId');
            $table->dateTime('start', 3);
            $table->dateTime('finish', 3)->nullable();
            $table->string('duration');
            $table->integer('possition')->nullable()->unsigned();
            $table->boolean('top')->nullable();
            $table->timestamps();
            $table->foreign('driverId')->references('id')->on('drivers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
