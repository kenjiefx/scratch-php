<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentsIterator;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnCreateTemplateEvent;
use Kenjiefx\ScratchPHP\App\Exceptions\TemplateAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateController
{

    private EventDispatcher $EventDispatcher;

    public readonly ComponentRegistry $ComponentRegistry;

    public function __construct(
        public readonly TemplateModel $TemplateModel
    ){
        $this->EventDispatcher = new EventDispatcher;
        $this->ComponentRegistry = new ComponentRegistry();
    }

    public function getpath(){
        $ThemeController = new ThemeController();
        return $ThemeController->path()->templates 
            . $this->TemplateModel->name
            . '.php';
    }

    public function getdir(){
        $ThemeController = new ThemeController();
        $ThemeController->mount(AppSettings::getThemeName());
        return $ThemeController->path()->templates;
    }

    public function create():void {

        $ThemeController = new ThemeController();
        $ThemeController->mount(AppSettings::getThemeName());

        $templatepath = $ThemeController->path()->templates . $this->TemplateModel->name . '.php';
        if (file_exists($templatepath)) {
            throw new TemplateAlreadyExistsException($this->TemplateModel->name);
        }

        $contents = $this->EventDispatcher->dispatchEvent(OnCreateTemplateEvent::class,$this);
        file_put_contents($templatepath,$contents ?? '');

    }

}
