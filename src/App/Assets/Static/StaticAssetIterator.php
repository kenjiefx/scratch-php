<?php 

namespace Kenjiefx\ScratchPHP\App\Assets\Static;

class StaticAssetIterator implements \Iterator {

    private array $assets;
    private int $position = 0;

    public function __construct(array $assets) {
        $this->assets = $assets;
    }

    public function current(): StaticAssetModel {
        return $this->assets[$this->position];
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
        return isset($this->assets[$this->position]);
    }

}