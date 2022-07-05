<?php

use App\Models\SateliteType;
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
        Schema::create('satelites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description');
            $table->foreignIdFor(SateliteType::class, 'type_id')->constrained('satelite_types');
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
        Schema::dropIfExists('satelites');
    }
};
