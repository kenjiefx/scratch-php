<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentEventDTO {

    public string $content;

    public function __construct(
        public readonly ComponentController $ComponentController
    ){
        $this->content = '';
    }

}