<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentModel {

    public function __construct(
        public readonly string $namespace,
        public readonly string $id
    ) {}

}