<?php 

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageData {
    
    public function __construct(
        public readonly array $data
    ) {}

    /**
     * Returns the value for a given key, or null if the key does not exist.
     * Can only be set during construction.
     */
    public function get(string $key): mixed {
        return $this->data[$key] ?? null;
    }

}