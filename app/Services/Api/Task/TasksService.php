<?php

namespace App\Services\Api\Task;

use App\Entity\Task\TaskSortingFields;
use App\Entity\Task\TaskStatus;
use App\Models\Task;
use App\Services\Api\Task\Dto\TaskDto;
use App\Services\Api\Task\Dto\TaskIndexDto;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;

class TasksService
{
    /**
     * @param \App\Services\Api\Task\Dto\TaskIndexDto $dto
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function index(TaskIndexDto $dto): Collection
    {
        $tasks_query = Task::query()
            ->where([
                ['user_id', '=', auth()->user()->id],
            ]);

        return self::makeIndexSearchQuery($tasks_query, $dto)
            ->get();
    }

    /**
     * @param \App\Models\Task $task
     *
     * @return \App\Models\Task
     */
    public static function show(Task $task): Task
    {
        $task->children = Task::defaultOrder()
            ->descendantsOf($task['id'])
            ->toTree();

        return $task;
    }

    /**
     * @param \App\Services\Api\Task\Dto\TaskDto $dto
     *
     * @return \App\Models\Task
     */
    public static function create(TaskDto $dto): Task
    {
        $task            = new Task();
        $task->user_id   = auth()->user()->id;
        $task->parent_id = $dto->getParentTaskId();

        return self::update($task, $dto);
    }

    /**
     * @param \App\Models\Task                   $task
     * @param \App\Services\Api\Task\Dto\TaskDto $dto
     *
     * @return \App\Models\Task
     */
    public static function update(Task $task, TaskDto $dto): Task
    {
        $task->priority    = $dto->getPriority() ?? $task->priority;
        $task->title       = $dto->getTitle() ?? $task->title;
        $task->description = $dto->getDescription() ?? $task->description;

        if ($dto->getStatus()?->value == TaskStatus::DONE->value) {
            $task->status       = $dto->getStatus()->value ?? $task->status;
            $task->completed_at = $dto->getStatus()?->value == TaskStatus::DONE->value
                    ? Carbon::now()
                    : null;
        }
        $task->save();

        return $task;
    }

    /**
     * @param \App\Models\Task $task
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function delete(Task $task): JsonResponse
    {
        $result = self::taskCanBeDeleted($task);

        if ($result['success']) {
            $task->delete();
        }

        return response()->json($result);
    }

    /**
     * @param \App\Models\Task $task
     *
     * @return array
     */
    public static function taskCanBeDeleted(Task $task): array
    {
        if ($task->status === TaskStatus::DONE->value) {
            return [
                'success' => false,
                'message' => "You can't remove tasks in status 'done'",
            ];
        }

        return [
            'success' => true,
            'message' => 'Task has been removed',
        ];
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder   $tasks_query
     * @param \App\Services\Api\Task\Dto\TaskIndexDto $dto
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private static function makeIndexSearchQuery(
        Builder $tasks_query,
        TaskIndexDto $dto
    ): Builder {
        $filters = [
            'status'   => $dto->getStatus(),
            'priority' => $dto->getPriority(),
        ];

        foreach ($filters as $column => $value) {
            if ($value) {
                $tasks_query->where($column, '=', $value);
            }
        }

        $like_filters = [
            'title'       => $dto->getTitle(),
            'description' => $dto->getDescription(),
        ];

        foreach ($like_filters as $column => $value) {
            if ($value) {
                $tasks_query->where($column, 'ILIKE', '%' . $value . '%');
            }
        }

        if ($dto->getSorting()) {
            foreach ($dto->getSorting() as $sort_element) {
                $tasks_query->orderBy(
                    TaskSortingFields::getDataBaseColumn(
                        TaskSortingFields::tryFrom($sort_element['field'])
                    ),
                    $sort_element['order']
                );
            }
        }

        return $tasks_query;
    }
}
