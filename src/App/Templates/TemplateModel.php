<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;

class TemplateModel
{

    public function __construct(
        public readonly string $name
    ){
        
    }

}
