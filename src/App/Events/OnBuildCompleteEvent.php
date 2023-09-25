<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class OnBuildCompleteEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = OnBuildCompleteEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
