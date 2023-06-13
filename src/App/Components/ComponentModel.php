<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentModel
{
    public function __construct(
        private string $name
    ){

    }

    public function getName(){
        return $this->name;
    }
}
