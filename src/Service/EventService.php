<?php

namespace Buses\Service;

use Buses\Model\Event;
use Cron\CronExpression;

class EventService
{
    /**
     * @var Event[] $events
     */
    private $events;

    public function __construct()
    {
        $this->events = [
            (new Event())
                ->setName('demo_event')
                ->setDescription('Pop the kettle on')
                ->setDate('* * * * * *'),
        ];
    }

    /**
     * Returns any events that are currently happening
     * @return Event[]
     */
    public function getCurrentEvents()
    {
        return array_filter($this->events, function ($event) {
            /** @var $event Event */
            $cron = CronExpression::factory($event->getDate());
            return $cron->isDue();
        });
    }
}