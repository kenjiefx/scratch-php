<?php

use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

#[\Attribute]
class ListensTo
{
    public function __construct(private EventInterface $Event){
        
    }

    public function getEvent():EventInterface{
        return $this->Event;
    }
}
