<?php

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
        Schema::create('spectors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('start_w');
            $table->integer('end_w');
            $table->foreignIdFor(Sensor::class)->constrained("sensors");
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
        Schema::dropIfExists('spectors');
    }
};
