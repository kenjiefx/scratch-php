<?php

namespace Kenjiefx\ScratchPHP\App\Templates;

class TemplateEventDTO {

    public string $content;

    public function __construct(
        public readonly TemplateController $TemplateController
    ){

    }

}