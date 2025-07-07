<?php

namespace Kenjiefx\ScratchPHP\App\Blocks;
use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;

class BlocksIterator implements \Iterator
{
    private $models;
    private $position;

    public function __construct(array $models) {
        $this->models = $models;
        $this->position = 0;
    }

    public function current(): BlockModel {
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
