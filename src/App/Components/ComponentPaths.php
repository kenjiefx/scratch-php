<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentPaths {
    public function __construct(
        private string $dir,
        private string $name
    ){

    }

    public function html(): string {
        /** @TODO Configuration for using other extension other than .php */
        return $this->dir . $this->name . '.php';
    }

    public function css(): string {
        return $this->dir . $this->name . '.css';
    }

    public function js(): string {
        return $this->dir . $this->name . '.js';
    }

    public function ts(): string {
        return $this->dir . $this->name . '.ts';
    }
}
