<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNaryadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('naryads', function (Blueprint $table) {
                $table->Bigincrements('id');
                $table->integer('branch_id')->unsigned();
                $table->integer('unit_id')->unsigned();  // підрозділ
                $table->integer('warden_id')->unsigned(); // наглядач
                $table->integer('adjuster_id')->unsigned(); // допускач
                $table->string('brigade_m');
                $table->string('brigade_e');
                $table->integer('substation_id')->unsigned(); // допускач
                $table->integer('works_spec_id')->unsigned(); // id напрямку робіт
                $table->integer('line_id')->unsigned(); // id напрямку робіт
                $table->text('ojects');
                $table->text('tasks');
                $table->dateTime('w_begin');
                $table->dateTime('w_end');
	        	$table->text('sep_instrs');
                $table->dateTime('order_date');
                $table->text('order_creator');
                $table->dateTime('order_longto');
                $table->text('order_longer');
                $table->timestamps();
                $table->foreign('branch_id')->references('id')->on('dict_branches')->onDelete('cascade');
                $table->foreign('unit_id')->references('id')->on('dict_units')->onDelete('cascade');
                $table->foreign('warden_id')->references('id')->on('dict_wardens')->onDelete('cascade');
                $table->foreign('adjuster_id')->references('id')->on('dict_adjusters')->onDelete('cascade');
                $table->foreign('substation_id')->references('id')->on('dict_substations')->onDelete('cascade');
                $table->index(['branch_id','unit_id']);
                $table->index(['branch_id','substation_id']);
                $table->index(['branch_id','works_spec_id']);
                $table->index(['branch_id','warden_id']);
                $table->index(['branch_id','adjuster_id']);
                $table->index(['branch_id','w_begin']);
                $table->index(['branch_id','w_end']);
                $table->index(['branch_id','ojects']);
                $table->index(['branch_id','tasks']);
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('naryads');
    }
}
