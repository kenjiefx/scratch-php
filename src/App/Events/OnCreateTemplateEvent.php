<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class OnCreateTemplateEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = OnCreateTemplateEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
