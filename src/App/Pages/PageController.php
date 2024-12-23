<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;

class PageController 
{

    private TemplateController $TemplateController;

    public function __construct(
        public readonly PageModel $PageModel
    ){
        $this->TemplateController = new TemplateController(
            $this->PageModel->TemplateModel
        );
    }

    public function template(): TemplateController {
        return $this->TemplateController;
    }

    public function relpath(): string {
        return ($this->PageModel->dir === '') 
            ? '' : '/' . $this->PageModel->dir;
    }

    public function assetref(): string {
        $random = AppSettings::build()
                    ->useRandomAssetsFileNames();
        return ($random === true) 
            ? $this->PageModel->id : $this->PageModel->name;
    }

}
