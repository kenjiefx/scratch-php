<?php

namespace Kenjiefx\ScratchPHP\App\Build;

use Kenjiefx\ScratchPHP\App\Components\ComponentController;

class CollectEventDTO {
    public string $content;

    public function __construct(
        public readonly ComponentController $ComponentController
    ){
        
    }
}