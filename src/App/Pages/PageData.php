<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageData {
    public function __construct(
        private array $data
    ){

    }

    public function set(
        string $key,
        mixed $value
    ) {
        if (!isset($this->data[$key])) {
            $this->data[$key] = $value;
        }
    }

    public function get(
        string $key
    ): mixed {
        return $this->data[$key] ?? null;
    }
}