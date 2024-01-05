<?php

namespace App\Entity\Task;

enum TaskStatus: string
{
    case TODO = 'todo';
    case DONE = 'done';
}
