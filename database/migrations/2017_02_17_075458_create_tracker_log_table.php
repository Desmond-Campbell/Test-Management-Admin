<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackerLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracker_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string( 'hash', 40 );
            $table->string( 'ip', 32 )->nullable();
            $table->string( 'host' )->nullable();
            $table->string( 'uri' );
            $table->decimal( 'timevalue', 8, 2 )->nullable()->default(0);
            $table->string( 'language', 50 )->nullable();
            $table->string( 'agent' )->nullable();
            $table->string( 'referer' )->nullable();
            $table->text( 'input' )->nullable();
            $table->text( 'request' )->nullable();
            $table->text( 'files' )->nullable();
            $table->decimal( 'document_height', 8, 2 )->nullable();
            $table->decimal( 'document_width', 8, 2 )->nullable();
            $table->decimal( 'window_height', 8, 2 )->nullable();
            $table->decimal( 'window_width', 8, 2 )->nullable();
            $table->integer( 'user_id' );
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
        Schema::dropIfExists('tracker_log');
    }
}
