<?php 

namespace Kenjiefx\ScratchPHP\App\Extensions;

class ExtensionIterator implements \Iterator {

    private array $extensions;
    private int $position = 0;

    public function __construct(array $extensions) {
        $this->extensions = $extensions;
    }

    public function current(): ExtensionModel {
        return $this->extensions[$this->position];
    }

    public function key(): mixed {
        return $this->position;
    }

    public function next(): void {
        ++$this->position;
    }

    public function rewind(): void {
        $this->position = 0;
    }

    public function valid(): bool {
        return isset($this->extensions[$this->position]);
    }

}