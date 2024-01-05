<?php

namespace App\Http\Requests\Api\Task;

use App\Entity\SortingOrder;
use App\Entity\Task\TaskSortingFields;
use App\Entity\Task\TaskStatus;
use App\Services\Api\Task\Dto\TaskIndexDto;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class TasksIndexRequest extends FormRequest
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
            'priority'        => [
                'nullable',
                'integer',
                'min:1',
                'max:5',
            ],
            'status'          => [
                'nullable',
                new Enum(TaskStatus::class),
                Rule::in(array_map(
                    fn (
                        TaskStatus $taskStatus
                    ) => $taskStatus->value,
                    TaskStatus::cases()
                )),
            ],
            'title'           => [
                'nullable',
                'string',
            ],
            'description'     => [
                'nullable',
                'string',
            ],
            'sorting'         => [
                'nullable',
                'array',
            ],
            'sorting.*.field' => [
                'required',
                new Enum(TaskSortingFields::class),
                Rule::in(array_map(
                    fn (
                        TaskSortingFields $field
                    ) => $field->value,
                    TaskSortingFields::cases()
                )),
            ],
            'sorting.*.order' => [
                'required',
                new Enum(SortingOrder::class),
                Rule::in(array_map(
                    fn (
                        SortingOrder $order
                    ) => $order->value,
                    SortingOrder::cases()
                )),
            ],
        ];
    }

    public function getDto(): TaskIndexDto
    {
        //        dd($this->get('sorting'));
        return new TaskIndexDto(
            priority: $this->get('priority'),
            status: TaskStatus::tryFrom($this->get('status')),
            title: $this->get('title'),
            description: $this->get('description'),
            sorting: $this->get('sorting'),
        );
    }
}
