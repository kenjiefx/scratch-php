<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnCollectComponentCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCollectComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCollectTemplateCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCollectTemplateJsEvent;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

abstract class AbstractCollector
{
    protected ?string $collectedAsset = null;

    protected string $filetype;

    private EventDispatcher $EventDispatcher;

    public function __construct(
        protected ThemeController $ThemeController
    ){
        $this->EventDispatcher = new EventDispatcher;
    }

    public function collect(TemplateController $TemplateController){
        $content = '';
        $ComponentsIterator = $TemplateController->ComponentRegistry->get();
        foreach ($ComponentsIterator as $ComponentController) {
            $filetype = $this->filetype;
            $path = $ComponentController->paths()->$filetype();
            $CollectEventDTO = new CollectEventDTO($ComponentController);
            $CollectEventDTO->content = file_get_contents($path);
            if ($filetype === 'js') {
                $this->EventDispatcher->dispatchEvent(
                    OnCollectComponentJsEvent::class, 
                    $CollectEventDTO
                );
            } else {
                $this->EventDispatcher->dispatchEvent(
                    OnCollectComponentCssEvent::class, 
                    $CollectEventDTO
                );
            }
            if (file_exists($path)) {
                $content .= $CollectEventDTO->content;
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
        $filetype = $this->filetype;
        $CollectTemplateAssetEventDTO 
            = new CollectTemplateAssetEventDTO(
                $TemplateController
            );
        $path = $TemplateController->getdir() . '/' . $name . '.' . $filetype;
        if (file_exists($path)) {
            $CollectTemplateAssetEventDTO->content 
                = file_get_contents($path);
        } else {
            $CollectTemplateAssetEventDTO->content = '';
        }
        if ($filetype === 'js') {
            $this->EventDispatcher->dispatchEvent(
                OnCollectTemplateJsEvent::class, 
                $CollectTemplateAssetEventDTO
            );
        } else {
            $this->EventDispatcher->dispatchEvent(
                OnCollectTemplateCssEvent::class, 
                $CollectTemplateAssetEventDTO
            );
        }
        return $CollectTemplateAssetEventDTO->content;
    }
}
