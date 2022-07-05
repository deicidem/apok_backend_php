<?php

use App\Models\File;
use App\Models\TaskResult;
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
        Schema::create('task_result_files', function (Blueprint $table) {
            $table->id();   
            $table->string('name');
            $table->foreignIdFor(TaskResult::class)
                ->constrained('task_results')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(File::class)
                ->constrained('files')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();  
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
        Schema::dropIfExists('task_result_files');
    }
};
