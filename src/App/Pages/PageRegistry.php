<?php 

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageRegistry implements \Iterator {

    private array $pages = [];
    private int $position = 0;

    public function __construct(array $pages) {
        $this->pages = $pages;
    }

    public function current(): PageModel {
        return $this->pages[$this->position];
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
        return isset($this->pages[$this->position]);
    }

}