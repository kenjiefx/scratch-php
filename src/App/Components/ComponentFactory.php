<?php

namespace Kenjiefx\ScratchPHP\App\Components;

class ComponentFactory
{
    public static function create(string $name): ComponentModel {
        $ComponentModel = new ComponentModel($name);
        return $ComponentModel;
    }
}
