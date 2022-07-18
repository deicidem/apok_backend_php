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
        Schema::create('logs', function (Blueprint $t) {
            $t->increments('id');
            $t->text('description')->nullable();
            $t->string('origin', 200)->nullable();
            $t->enum('type', ['log', 'store', 'change', 'delete']);
            $t->enum('result', ['success', 'neutral', 'failure']);
            $t->enum('level', ['emergency', 'alert', 'critical', 'error', 'warning', 'notice', 'info', 'debug']);
            $t->string('token', 100)->nullable();
            $t->ipAddress('ip');
            $t->string('user_agent', 200)->nullable();
            $t->string('session', 100)->nullable();
            $t->timestamps();
        });
    }
    // public function up()
    // {
    //     Schema::create('logs', function (Blueprint $table) {
    //         $table->bigIncrements('id');
    // // Below is what are included in logger
    // // you will know what it means later 
    // $table->longText('message');
    // $table->longText('context');
    // $table->string('level')->index();
    // $table->string('level_name');
    // $table->string('channel')->index();
    // $table->string('record_datetime');
    // $table->longText('extra');
    // $table->longText('formatted');
    // // Additional custom fields I added 
    // $table->string('remote_addr')->nullable();
    // $table->string('user_agent')->nullable();
    // $table->dateTime('created_at')->nullable();
    // // As you can see, I comment this out, because I don't need
    // // updated_at
    // // $table->timestamps();
    //     });
    // }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
};
