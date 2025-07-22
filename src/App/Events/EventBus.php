<?php

namespace Kenjiefx\ScratchPHP\App\Events;

class EventBus {

    public function __construct(
        private EventRegistry $eventRegistry
    ) {}

    public function dispatchEvent(EventInterface $event) {
        $eventNamespace = get_class($event);
        $callables = $this->eventRegistry->getEventListeners($eventNamespace);
        foreach ($callables as $callable) {
            $callable($event);
        }
    }

}