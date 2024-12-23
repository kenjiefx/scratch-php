<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentsIterator implements \Iterator
{
    private $ComponentControllers;
    private $position;

    public function __construct(array $ComponentControllers) {
        $this->ComponentControllers = $ComponentControllers;
        $this->position = 0;
    }

    public function current(): ComponentController {
        return $this->ComponentControllers[$this->position];
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
        return isset($this->ComponentControllers[$this->position]);
    }
}
