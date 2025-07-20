<?php

namespace Kenjiefx\ScratchPHP\App\Events;

class EventRegistry
{
    /**
     * Stores event listeners keyed by event namespace.
     *
     * @var array<string, callable[]>
     */
    private static array $eventListeners = [];

    /**
     * Registers a listener (callable) to an event.
     *
     * @param string $eventNamespace
     * @param callable $listener
     */
    public function addEventListener(string $eventNamespace, callable $listener): void
    {
        static::$eventListeners[$eventNamespace][] = $listener;
    }

    /**
     * Gets all listeners for a given event.
     *
     * @param string $eventNamespace
     * @return callable[]
     */
    public function getEventListeners(string $eventNamespace): array
    {
        return static::$eventListeners[$eventNamespace] ?? [];
    }
}
