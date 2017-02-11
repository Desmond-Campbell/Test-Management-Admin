<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('error_details', function (Blueprint $table) {
            $table->increments('id');
            $table->string('error_id', 16);
            $table->string('ip', 32);
            $table->string('browser')->nullable();
            $table->string('url');
            $table->text('extract')->nullable();
            $table->text('variables')->nullable();
            $table->text('trace')->nullable();
            $table->boolean('debug')->nullable()->default(0);
            $table->integer('status')->nullable()->default(0);
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
        Schema::dropIfExists('error_details');
    }
}
