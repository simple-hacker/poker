<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->timestamp('start_time')->useCurrent();
            $table->timestamp('end_time')->nullable();
            $table->string('currency', 6)->nullable();
            $table->unsignedBigInteger('limit_id')->nullable();
            $table->unsignedBigInteger('variant_id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedInteger('entries')->default(0);
            $table->unsignedInteger('position')->default(0);
            $table->unsignedInteger('prize_pool')->default(0);
            $table->string('location')->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tournaments');
    }
}
