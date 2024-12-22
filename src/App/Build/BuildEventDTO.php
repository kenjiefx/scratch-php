<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Pages\PageController;

class BuildEventDTO {

    public string $content;

    public function __construct(
        public readonly PageController $PageController
    ){
        
    }

}