<?php

use App\Models\ProcessingLevel;
use App\Models\Sensor;
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
        Schema::create('dzzs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('date');
            $table->integer('round');
            $table->integer('route');
            $table->integer('cloudiness');
            $table->text('description');
            $table->foreignIdFor(Sensor::class);
            $table->foreignIdFor(ProcessingLevel::class);
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
        Schema::dropIfExists('dzzs');
    }
};
