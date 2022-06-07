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
            $table->date('date')->nullable();
            $table->integer('round')->nullable();
            $table->integer('route')->nullable();
            $table->integer('cloudiness')->nullable();
            $table->text('description')->nullable();
            $table->foreignIdFor(Sensor::class)->nullable();
            $table->foreignIdFor(ProcessingLevel::class)->nullable();
            $table->unsignedBigInteger('preview_id')->nullable();
            $table->unsignedBigInteger('directory_id');           
            $table->foreign('preview_id')->references('id')->on('files');
            $table->foreign('directory_id')->references('id')->on('files');
            $table->multipolygon('geography')->nullable();
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
        Schema:: dropIfExists('dzzs');
    }
};
