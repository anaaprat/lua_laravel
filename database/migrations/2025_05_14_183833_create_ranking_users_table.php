<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRankingUsersTable extends Migration
{
    public function up()
    {
        Schema::create('ranking_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ranking_id');
            $table->unsignedBigInteger('user_id');
            $table->integer('points')->default(0);
            $table->integer('month_record')->default(0);
            $table->timestamps();
            
            $table->foreign('ranking_id')->references('id')->on('rankings')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->unique(['ranking_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('ranking_users');
    }
}