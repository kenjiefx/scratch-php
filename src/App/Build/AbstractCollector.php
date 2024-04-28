<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

abstract class AbstractCollector
{
    protected ?string $collectedAsset = null;

    public function __construct(
        protected ThemeController $ThemeController
    ){

    }

    public function collect(TemplateController $TemplateController){
        $collectedAsset = '';
        foreach ($TemplateController->getUtilizedComponents() as $key => $ComponentModel) {
            $name = $ComponentModel->getName();
            $path = $this->ThemeController->getComponentsDirPath($name).'/'.$name.'.'.$this->fileType;
            if (file_exists($path)) {
                $collectedAsset .= file_get_contents($path);
            }
        }
        $collectedAsset .= $this->templateAssets($TemplateController);
        return $this->globalSrc().$collectedAsset;
    }

    public function globalSrc() {
        if (null===$this->collectedAsset) {
            $this->collectedAsset = '';
            $assetsDirPath = $this->ThemeController->getAssetsDirPath();
            foreach(scandir($assetsDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                if (explode('.',$fileName)[1]!==$this->fileType) continue; 
                $assetsPath            = $assetsDirPath.'/'.$fileName;
                $this->collectedAsset .= file_get_contents($assetsPath);
            }
        }
        return $this->collectedAsset;
    }

    private function templateAssets(TemplateController $TemplateController){
        $templateName      = $TemplateController->getTemplateName();
        $templateAssetPath = $TemplateController->getTemplatesDir().'/'.$templateName.'.'.$this->fileType;
        if (file_exists($templateAssetPath)) {
            return file_get_contents($templateAssetPath);
        }
        return '';
    }
}
