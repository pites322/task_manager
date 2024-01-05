<?php

namespace App\Entity;

enum SortingOrder: string
{
    case ASK  = 'asc';
    case DESC = 'desc';
}
