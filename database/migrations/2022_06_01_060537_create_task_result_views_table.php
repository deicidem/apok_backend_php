<?php

use App\Models\File;
use App\Models\TaskResult;
use App\Models\TaskResultViewType;
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
            $table->foreignIdFor(TaskResultViewType::class, 'type_id');
            $table->foreignIdFor(TaskResult::class);
            $table->foreignIdFor(File::class, 'preview_id');
            $table->polygon('geography')->nullable();
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
