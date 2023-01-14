<?php

namespace App\Events;

use Core\UseCase\Interfaces\EventManagerInterface;

class VideoEvent implements EventManagerInterface
{
    public function dispatch(object $event): void
    {
        event($event);
    }
}


