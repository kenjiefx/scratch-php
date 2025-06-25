<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class ComponentJSCreatedEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = ComponentJSCreatedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
