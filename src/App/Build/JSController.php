<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class JSController
{
    private string|null $globalJS = null;

    public function __construct(
        private ThemeController $themeController
    ){

    }

    public function buildGlobalJS(){
        if (null===$this->globalJS) {
            $this->globalJS = '';
            $assetsDir = $this->themeController->getAssetsDir();
            foreach(scandir($assetsDir) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                if (explode('.',$fileName)[1]!=='js') continue; 
                $assetPath = $assetsDir.'/'.$fileName;
                $this->globalJS .= PHP_EOL.file_get_contents($assetPath);
            }
        }
        return $this->globalJS;
    }

    public function buildComponentJS(
        array $components
    ){
        $compiledJS = '';
        foreach ($components as $component) {
            $JSFilePath = $this->themeController->getComponentDir($component).'/'.$component.'.js';
            if (file_exists($JSFilePath)) {
                $compiledJS .= PHP_EOL.file_get_contents($JSFilePath);
            }
        }
        return $compiledJS;
    }
}
