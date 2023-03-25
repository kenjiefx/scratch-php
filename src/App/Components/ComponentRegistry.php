<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentRegistry
{

    private static array $componentModels;

    public function __construct(
        private ThemeController $themeController
    ){
        if (!isset(static::$componentModels)) {
            static::$componentModels = [];
        }
    }

    public function register(
        string $templateName,
        string $componentName
    ){
        $componentStaticKey = $this->makeStaticKey($templateName,$componentName);
        if (!isset(static::$componentModels[$componentStaticKey])) {
            $componentDir = $this->themeController->getComponentDir($componentName);
            $componentHtmlPath = $componentDir.'/'.$componentName.'.php';
            $componentJsPath = $componentDir.'/'.$componentName.'.js';
            $componentCssPath = $componentDir.'/'.$componentName.'.js';
            $componentModel = new ComponentModel(
                componentName: $componentName,
                templateRefName: $templateName, 
                componentHtmlPath: $componentHtmlPath,
                componentJsPath: $componentJsPath,
                componentCssPath: $componentCssPath
            );
        } else {
            $componentModel = static::$componentModels[$componentStaticKey];
        }
        return $componentModel;
    }

    public function makeStaticKey(
        string $templateName,
        string $componentName
    ){
        return $templateName.':'.$componentName;
    }
}
