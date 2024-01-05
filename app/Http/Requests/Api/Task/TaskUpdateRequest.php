<?php

namespace App\Http\Requests\Api\Task;

use App\Entity\Task\TaskStatus;
use App\Rules\NestedCheck;
use App\Services\Api\Task\Dto\TaskDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TaskUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<mixed>|\Illuminate\Contracts\Validation\ValidationRule|string>
     */
    public function rules(): array
    {
        return [
            'priority'    => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],
            'status'      => [
                'nullable',
                new Enum(TaskStatus::class),
                Rule::in(array_map(
                    fn (
                        TaskStatus $taskStatus
                    ) => $taskStatus->value,
                    TaskStatus::cases()
                )),
                new NestedCheck(),
            ],
            'title'       => [
                'nullable',
                'string',
            ],
            'description' => [
                'nullable',
                'string',
            ],
        ];
    }

    public function getDto(): TaskDto
    {
        return new TaskDto(
            priority: $this->get('priority'),
            status: TaskStatus::tryFrom($this->get('status')),
            title: $this->get('title'),
            description: $this->get('description'),
            parent_task_id: $this->get('parent_task_id'),
        );
    }
}
