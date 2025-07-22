<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentIterator implements \Iterator {

    private int $position = 0;
    private array $components;

    public function __construct(array $components) {
        $this->components = $components;
    }

    public function current(): ComponentModel {
        return $this->components[$this->position];
    }

    public function key(): int {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->components[$this->position]);
    }

}