<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentController
{

    private ComponentModel $componentModel;
    private string $componentName;

    public function __construct(
        private ThemeController $themeController
    ){
        AppSettings::load();
    }

    public function setComponentName(
        string $componentName
    ){
        $this->componentName = $componentName;
    }

    public function getComponentDir(){
        return $this->themeController->getComponentDir($this->componentName);
    }


    public function createComponent(
        string $componentName
    ){
        $this->setComponentName($componentName);
        $this->themeController->useTheme(AppSettings::getThemeName());

        $componentDir = $this->getComponentDir();

        if (is_dir($componentDir)) {
            $error  = 'Component Already Exists! The component you are trying to create named "';
            $error .= $componentName.'" alrady exists in the theme.';
            throw new \Exception($error);
        }

        $componentHtmlPath = $componentDir.'/'.$componentName.'.php';
        $componentJsPath   = $componentDir.'/'.$componentName.'.js';
        $componentCssPath  = $componentDir.'/'.$componentName.'.css';

        $componentHtml = $componentCss = $componentJs = '';

        $componentModel = new ComponentModel(
            componentName:     $componentName,
            templateRefName:   '',
            componentHtmlPath: $componentHtmlPath,
            componentJsPath:   $componentJsPath,
            componentCssPath:  $componentCssPath
        );

        # Extensions
        foreach (AppSettings::extensions()->getExtensions() as $extension) {
            $componentHtml = $extension->onCreateComponentContent($componentModel,$componentHtml);
            $componentCss  = $extension->onCreateComponentCSS($componentModel,$componentCss);
            $componentJs   = $extension->onCreateComponentJS($componentModel,$componentJs);
        }

        mkdir($componentDir);

        file_put_contents($componentHtmlPath,$componentHtml);
        file_put_contents($componentJsPath,$componentJs);
        file_put_contents($componentCssPath,$componentCss);

    }
}
