<?php 

namespace Kenjiefx\ScratchPHP\App\Collectibles;

/**
 * CollectibleRegistry class manages a collection of CollectibleModel instances, 
 * to be used by a Collector implementing CollectorInterface.
 */
class CollectibleRegistry implements \Iterator {

    private array $collectibles = [];
    private int $position = 0;

    public function addCollectible(CollectibleModel $collectible): void {
        $this->collectibles[] = $collectible;
    }

    public function current(): CollectibleModel {
        return $this->collectibles[$this->position];
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
        return isset($this->collectibles[$this->position]);
    }
}