<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageIterator implements \Iterator
{
    private $paths;
    private $position;

    public function __construct(array $paths) {
        $this->paths = $paths;
        $this->position = 0;
    }

    public function current(): PageController {
        $PageJSON = new PageJSON();
        $PageJSON->unpackFromSrc($this->paths[$this->position]);
        $PageModel = PageFactory::create($PageJSON);
        $PageController = new PageController($PageModel);
        return $PageController;
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
        return isset($this->paths[$this->position]);
    }
}
