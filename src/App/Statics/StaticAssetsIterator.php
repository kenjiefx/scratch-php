<?php 

namespace Kenjiefx\ScratchPHP\App\Statics;

class StaticAssetsIterator implements \Iterator
{
    private $assets = [];
    private $position = 0;

    public function __construct(array $assets)
    {
        $this->assets = $assets;
    }

    public function current(): StaticAssetsModel
    {
        return $this->assets[$this->position];
    }

    public function key(): int
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->assets[$this->position]);
    }
}