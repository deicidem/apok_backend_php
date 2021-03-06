<?php

use App\Models\DataType;
use App\Models\Dzz;
use App\Models\Plan;
use App\Models\TaskResult;
use App\Models\TaskResultView;
use App\Models\FileType;
use App\Models\User;
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
            $table->foreignIdFor(DataType::class, 'type_id')->constrained('data_types');
            $table->foreignIdFor(User::class)->nullable()->constrained("users")->cascadeOnDelete()
            ->cascadeOnUpdate();;
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
