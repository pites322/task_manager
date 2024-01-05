<?php

namespace App\Http\Resources\Task;

use Illuminate\Http\Request;

class NestedTaskResource extends TaskResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if($this->children) {
            $data['subtasks'] = NestedTaskResource::collection($this->children);
        }

        return $data;
    }

}
