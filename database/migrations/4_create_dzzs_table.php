<?php

use App\Models\File;
use App\Models\ProcessingLevel;
use App\Models\Satelite;
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
            $table->foreignIdFor(Satelite::class, 'satelite_id')->nullable()->constrained('satelites');
            $table->foreignIdFor(ProcessingLevel::class, 'processing_level_id')->nullable()->constrained('processing_levels');
            $table->foreignIdFor(File::class, 'preview_id')
                ->nullable()
                ->constrained('files')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(File::class, 'directory_id')
                ->nullable()
                ->constrained('files')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->geometry('geography')->nullable();
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
