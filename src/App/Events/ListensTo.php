<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

#[\Attribute]
class ListensTo
{
    private EventInterface $Event;

    public function __construct(string $EventInterfaceClass){
        $ReflectionObject = new \ReflectionObject(new $EventInterfaceClass());
        if (!$ReflectionObject->implementsInterface(EventInterface::class)) {
            throw new \Exception("Event class must implement EventInterface: $EventInterfaceClass");
        }
        $this->Event = $ReflectionObject->newInstance();
    }

    public function getEvent():EventInterface{
        return $this->Event;
    }
}
