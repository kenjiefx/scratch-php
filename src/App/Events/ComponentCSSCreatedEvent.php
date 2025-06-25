<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

/**
 * Event triggered when CSS for a component is created.
 */
class ComponentCSSCreatedEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = ComponentCSSCreatedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
