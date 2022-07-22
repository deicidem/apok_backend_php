<?php
use App\Models\Dzz;
use App\Models\Plan;
use App\Models\TaskStatus;
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('note')->nullable();
            $table->foreignIdFor(Plan::class, 'plan_id')->constrained('plans');
            $table->foreignIdFor(TaskStatus::class, 'status_id')->constrained('task_statuses');
            $table->foreignIdFor(User::class, 'user_id')->constrained('users')->cascadeOnDelete()
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
        Schema::dropIfExists('tasks');
    }
};
