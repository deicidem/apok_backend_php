<?php

use App\Models\Dzz;
use App\Models\Plan;
use App\Models\TaskResult;
use App\Models\TaskResultView;
use App\Models\FileType;
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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->foreignIdFor(Dzz::class)->nullable();
            $table->foreignIdFor(FileType::class, 'type_id');
            $table->foreignIdFor(TaskResult::class)->nullable();
            $table->foreignIdFor(TaskResultView::class)->nullable();
            $table->foreignIdFor(Plan::class)->nullable();
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
        Schema::dropIfExists('files');
    }
};
