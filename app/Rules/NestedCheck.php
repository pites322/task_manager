<?php

namespace App\Rules;

use App\Entity\Task\TaskStatus;
use App\Models\Task;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NestedCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        if ($value === TaskStatus::DONE->value) {
            $unfinished_tasks = Task::descendantsOf(
                request()->route()->task->id
            )
                ->where('status', TaskStatus::TODO->value)
                ->count();

            if ($unfinished_tasks) {
                $fail("You can't set status 'done' to task which has subtasks in status 'todo'");
            }
        }
    }
}
