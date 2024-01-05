<?php

namespace App\Policies;

use App\Entity\Task\TaskStatus;
use App\Http\Requests\Api\Task\TaskCreateRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    use HandlesAuthorization;

    /**
     * @param \App\Models\User                              $user
     * @param \App\Http\Requests\Api\Task\TaskCreateRequest $request
     *
     * @return bool
     */
    public function create(User $user, TaskCreateRequest $request): bool
    {
        if($request->parent_task_id) {
            $task = Task::query()->find($request->parent_task_id);

            return $user->id === $task->user_id;
        }

        return true;
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return bool
     */
    public function view(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return bool
     */
    public function update(User $user, Task $task): bool
    {
        return $user->id === $task->user_id;
    }

    /**
     * @param \App\Models\User $user
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Auth\Access\Response
     */
    public function delete(User $user, Task $task): Response
    {
        if ($task->user_id !== $user->id) {
            return Response::deny('You do not own this task.', 403);
        }

        if ($task->status === TaskStatus::DONE->value) {
            return Response::deny('You cannot delete a completed task.', 403);
        }

        return Response::allow();
    }
}
