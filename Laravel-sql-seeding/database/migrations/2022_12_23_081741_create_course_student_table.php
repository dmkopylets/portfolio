<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_student', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')->onDelete('cascade');
            $table->integer('student_id');
            $table->foreign('student_id')
                ->references('id')
                ->on('students')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['course_id','student_id'], 'course_student_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_student', function (Blueprint $table) {
            $table->dropUnique('course_student_unique');
        });
        Schema::dropIfExists('course_student');
    }
};
