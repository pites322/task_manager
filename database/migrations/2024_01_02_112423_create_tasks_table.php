<?php

use App\Entity\Task\TaskStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('status')->default(TaskStatus::TODO->value);
            $table->smallInteger('priority')->index();
            $table->string('title', 100)->index();
            $table->text('description');
            $table->unsignedInteger('_lft')->index();
            $table->unsignedInteger('_rgt')->index();
            $table->uuid('parent_id')->nullable()->index();
            $table->timestamps();
            $table->timestamp('completed_at')->nullable();

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
