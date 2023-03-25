<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class CSSController
{

    private string|null $globalCSS = null;

    public function __construct(
        private ThemeController $themeController
    ){

    }

    public function buildGlobalCSS(){
        if (null===$this->globalCSS) {
            $this->globalCSS = '';
            $assetsDir = $this->themeController->getAssetsDir();
            foreach(scandir($assetsDir) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                if (explode('.',$fileName)[1]!=='css') continue; 
                $assetPath = $assetsDir.'/'.$fileName;
                $this->globalCSS .= file_get_contents($assetPath);
            }
        }
        return $this->globalCSS;
    }

    public function buildComponentCSS(
        array $components
    ){
        $compiledCSS = '';
        foreach ($components as $component) {
            $CSSFilePath = $this->themeController->getComponentDir($component).'/'.$component.'.css';
            if (file_exists($CSSFilePath)) {
                $compiledCSS .= file_get_contents($CSSFilePath);
            }
        }
        return $compiledCSS;
    }


}
