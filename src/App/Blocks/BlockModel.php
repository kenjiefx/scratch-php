<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

class BlockModel {

    public function __construct(
        public readonly string $namespace,
        public readonly string $id
    ) {}

}