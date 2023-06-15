<?php

namespace Kenjiefx\ScratchPHP\App\Templates;

class TemplateFactory
{
    public static function create(string $name): TemplateModel {
        $TemplateModel = new TemplateModel($name);
        return $TemplateModel;
    }
}
