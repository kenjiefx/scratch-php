<?php

namespace Kenjiefx\ScratchPHP\App\Events;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

class BuildCompletedEvent implements EventInterface
{
    private $name;
    private $data;

    public function __construct(
        string $exportPath = '',
    ) {
        $this->name = BuildCompletedEvent::class;
    }

    public function getName():string {
        return $this->name;
    }

    public function getData():mixed {
        return $this->data;
    }

    public function getDistPath(): string {
        return $this->data['exportPath'];
    }
}
