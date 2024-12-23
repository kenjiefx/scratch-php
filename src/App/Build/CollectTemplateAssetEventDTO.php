<?php

namespace Kenjiefx\ScratchPHP\App\Build;

use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;

class CollectTemplateAssetEventDTO {
    public string $content;

    public function __construct(
        public readonly TemplateController $TemplateController
    ){
        
    }
}