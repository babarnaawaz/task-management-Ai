<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class TaskManager extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'task.manager';
    }
}