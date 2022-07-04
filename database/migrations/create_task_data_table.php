<?php

use App\Models\File;
use App\Models\PlanData;
use App\Models\Task;
use App\Models\TaskDataType;
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
        Schema::create('task_data', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Task::class)
                ->constrained('tasks')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(TaskDataType::class, 'type_id');
            $table->foreignIdFor(PlanData::class);
            $table->string('title');
            $table->string('text')->nullable();
            $table->foreignIdFor(File::class)
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
        Schema::dropIfExists('task_data');
    }
};
