<?php

namespace Kenjiefx\ScratchPHP\App\Build;

use Kenjiefx\ScratchPHP\App\Components\ComponentController;

class CollectComponentAssetEventDTO {
    public string $content;

    public function __construct(
        public readonly ComponentController $ComponentController
    ){
        
    }
}