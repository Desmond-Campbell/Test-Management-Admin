<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 40);
            $table->string('app_id');
            $table->string('app_env');
            $table->text('class')->nullable();
            $table->integer('offences')->nullable()->default(1);
            $table->text('description');
            $table->integer('code')->nullable();
            $table->integer('line')->nullable();
            $table->integer('codeline')->nullable();
            $table->text('message')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('errors');
    }
}
