<?php

namespace Kenjiefx\ScratchPHP\App\Events;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;

#[\Attribute]
class ListensTo
{
    /**
     * The event this class listens to.
     * This should be a fully qualified class name that implements EventInterface.
     * @var string
     */
    private string $event;

    public function __construct(string $eventInterfaceClass) {
        $reflectionClass = new \ReflectionClass($eventInterfaceClass);
        if (!$reflectionClass->implementsInterface(EventInterface::class)) {
            throw new \Exception("Event class must implement EventInterface: $eventInterfaceClass");
        }
        $this->event = $eventInterfaceClass;
    }

    /**
     * Get the event this class listens to.
     *
     * @return string The fully qualified class name of the event.
     */
    public function getEvent(): string {
        return $this->event;
    }
}
