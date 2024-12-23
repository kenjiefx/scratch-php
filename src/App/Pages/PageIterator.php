<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageIterator implements \Iterator
{
    private $PageControllers;
    private $position;

    public function __construct(array $PageControllers) {
        $this->PageControllers = $PageControllers;
        $this->position = 0;
    }

    public function current(): PageController {
        return $this->PageControllers[$this->position];
    }

    public function key(): mixed {
        return $this->position;
    }

    public function next(): void {
        $this->position++;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->PageControllers[$this->position]);
    }
}
