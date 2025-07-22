<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

class BlockIterator implements \Iterator {

    private int $position = 0;
    private array $blocks;

    public function __construct(array $blocks) {
        $this->blocks = $blocks;
    }

    public function current(): BlockModel {
        return $this->blocks[$this->position];
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
        return isset($this->blocks[$this->position]);
    }

}