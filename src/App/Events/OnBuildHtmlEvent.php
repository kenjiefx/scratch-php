<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Interfaces\EventInterface;

class OnBuildHtmlEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct() {
        $this->name = OnBuildHtmlEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }
}
