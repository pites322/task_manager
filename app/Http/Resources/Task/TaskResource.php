<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'priority'    => $this->priority,
            'status'      => $this->status,
            'title'       => $this->title,
            'description' => $this->description,
            'createdAt'   => $this->created_at,
            'completedAt' => $this->completed_at,
            'parentId'    => $this->parent_id,
        ];
    }
}
