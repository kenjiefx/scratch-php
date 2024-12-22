<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

abstract class AbstractCollector
{
    protected ?string $collectedAsset = null;

    protected string $filetype;

    public function __construct(
        protected ThemeController $ThemeController
    ){

    }

    public function collect(TemplateController $TemplateController){
        $content = '';
        $ComponentsIterator = $TemplateController->ComponentRegistry->get();
        foreach ($ComponentsIterator as $ComponentModel) {
            $ComponentController 
                = new ComponentController(
                    $ComponentModel
                );
            $filetype = $this->filetype;
            $path = $ComponentController->getdir()->$filetype();
            if (file_exists($path)) {
                $collectedAsset .= file_get_contents($path);
            }
        }
        $content .= $this->templateAssets($TemplateController);
        return $this->globalSrc() . $content;
    }

    public function globalSrc() {
        if (null===$this->collectedAsset) {
            $this->collectedAsset = '';
            $assetsDirPath = $this->ThemeController->path()->assets;
            foreach(scandir($assetsDirPath) as $fileName) {
                if ($fileName==='.'||$fileName==='..') continue;
                if (explode('.',$fileName)[1]!==$this->filetype) continue; 
                $assetsPath            = $assetsDirPath.'/'.$fileName;
                $this->collectedAsset .= file_get_contents($assetsPath);
            }
        }
        return $this->collectedAsset;
    }

    private function templateAssets(TemplateController $TemplateController){
        $name = $TemplateController->TemplateModel->name;
        $path = $TemplateController->getdir() . '/' . $name . '.' . $this->filetype;
        if (file_exists($path)) {
            return file_get_contents($path);
        }
        return '';
    }
}
