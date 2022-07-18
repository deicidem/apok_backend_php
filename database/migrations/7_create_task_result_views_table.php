<?php

use App\Models\File;
use App\Models\TaskResult;
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
        Schema::create('task_result_views', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignIdFor(DataType::class, 'type_id')->constrained("data_types");
            $table->foreignIdFor(TaskResult::class, 'task_result_id')
                ->constrained('task_results')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignIdFor(File::class, 'preview_id')
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
        Schema::dropIfExists('task_result_views');
    }
};
