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
        Schema::table('students', function (Blueprint $table) {
            $table->string('filiacao')->nullable();
            $table->string('rg')->nullable();
            $table->date('data_nascimento')->nullable();
            $table->string('estado_civil')->nullable();
            $table->string('naturalidade')->nullable();
            $table->string('titulo_eleitor')->nullable();
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            //
        });
    }
};
