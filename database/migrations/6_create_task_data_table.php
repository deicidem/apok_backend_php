<?php

use App\Models\File;
use App\Models\PlanData;
use App\Models\Task;
use App\Models\DataType;
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
            $table->foreignIdFor(DataType::class, 'type_id')->constrained("data_types");
            $table->foreignIdFor(PlanData::class)->constrained("plan_data");
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
