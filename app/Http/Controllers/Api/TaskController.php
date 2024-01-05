<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Task\TaskCreateRequest;
use App\Http\Requests\Api\Task\TasksIndexRequest;
use App\Http\Requests\Api\Task\TaskUpdateRequest;
use App\Http\Resources\Task\NestedTaskResource;
use App\Http\Resources\Task\TaskResource;
use App\Models\Task;
use App\Services\Api\Task\TasksService;
use Illuminate\Http\JsonResponse;
use Knuckles\Scribe\Attributes\Authenticated;

#[Authenticated]
class TaskController extends Controller
{
    /**
     * @param \App\Http\Requests\Api\Task\TasksIndexRequest $request
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(TasksIndexRequest $request)
    {
        return TaskResource::collection(
            TasksService::index($request->getDto())
        );
    }

    /**
     * @param \App\Models\Task $task
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \App\Http\Resources\Task\NestedTaskResource
     */
    public function show(Task $task): NestedTaskResource
    {
        $this->authorize('view', $task);

        return new NestedTaskResource(
            TasksService::show($task)
        );
    }

    /**
     * @param \App\Http\Requests\Api\Task\TaskCreateRequest $request
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \App\Http\Resources\Task\TaskResource
     */
    public function store(TaskCreateRequest $request): TaskResource
    {
        $this->authorize('store', $request);

        return new TaskResource(
            TasksService::create($request->getDto())
        );
    }

    /**
     * @param \App\Http\Requests\Api\Task\TaskUpdateRequest $request
     * @param \App\Models\Task                              $task
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \App\Http\Resources\Task\TaskResource
     */
    public function update(TaskUpdateRequest $request, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        return new TaskResource(
            TasksService::update($task, $request->getDto())
        );
    }

    /**
     * @param \App\Models\Task $task
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Task $task): JsonResponse
    {
        $this->authorize('delete', $task);

        return TasksService::delete($task);
    }
}
