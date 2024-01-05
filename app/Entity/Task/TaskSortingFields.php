<?php

namespace App\Entity\Task;

enum TaskSortingFields: string
{
    case CREATED_AT   = 'createdAt';
    case COMPLETED_AT = 'completedAt';
    case PRIORITY     = 'priority';

    /**
     * @param \App\Entity\Task\TaskSortingFields $field
     *
     * @return string
     */
    public static function getDataBaseColumn(TaskSortingFields $field): string
    {
        return match ($field) {
            self::CREATED_AT   => 'created_at',
            self::COMPLETED_AT => 'completed_at',
            self::PRIORITY     => 'priority',
        };
    }
}
