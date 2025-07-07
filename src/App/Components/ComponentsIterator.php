<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;

class ComponentsIterator implements \Iterator
{
    private $models;
    private $position;

    public function __construct(array $models) {
        $this->models = $models;
        $this->position = 0;
    }

    public function current(): ComponentModel {
        return $this->models[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        $this->position++;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->models[$this->position]);
    }
}
