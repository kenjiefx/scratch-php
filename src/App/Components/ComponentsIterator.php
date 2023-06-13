<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentsIterator implements \Iterator
{
    private $ComponentModels;
    private $position;

    public function __construct(array $ComponentModels) {
        $this->ComponentModels = $ComponentModels;
        $this->position = 0;
    }

    public function current():ComponentModel {
        return $this->ComponentModels[$this->position];
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position++;
    }

    public function rewind() {
        $this->position = 0;
    }

    public function valid() {
        return isset($this->ComponentModels[$this->position]);
    }
}
