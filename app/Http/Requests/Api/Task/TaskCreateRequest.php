<?php

namespace App\Http\Requests\Api\Task;

use App\Entity\Task\TaskStatus;
use App\Services\Api\Task\Dto\TaskDto;
use Illuminate\Foundation\Http\FormRequest;

class TaskCreateRequest extends FormRequest
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
            'priority'       => [
                'required',
                'integer',
                'min:1',
                'max:5',
            ],
            'title'          => [
                'required',
                'string',
            ],
            'description'    => [
                'required',
                'string',
            ],
            'parent_task_id' => [
                'nullable',
                'uuid',
            ],
        ];
    }

    public function getDto(): TaskDto
    {
        return new TaskDto(
            priority: $this->get('priority'),
            status: TaskStatus::TODO,
            title: $this->get('title'),
            description: $this->get('description'),
            parent_task_id: $this->get('parent_task_id'),
        );
    }
}
