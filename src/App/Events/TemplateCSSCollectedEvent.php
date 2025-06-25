<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

/**
 * Event triggered when CSS for a template is collected.
 */
class TemplateCSSCollectedEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = TemplateCSSCollectedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
