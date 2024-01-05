<?php

namespace App\Services\Api\Task\Dto;

use App\Entity\Task\TaskStatus;

class TaskIndexDto
{
    public function __construct(
        private readonly ?int $priority,
        private readonly ?TaskStatus $status,
        private readonly ?string $title,
        private readonly ?string $description,
        private readonly ?array $sorting,
    ) {
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function getStatus(): ?TaskStatus
    {
        return $this->status;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getSorting(): ?array
    {
        return $this->sorting;
    }

}
