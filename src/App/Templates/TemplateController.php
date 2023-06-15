<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentsIterator;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnCreateTemplateEvent;
use Kenjiefx\ScratchPHP\App\Exceptions\TemplateAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateController
{

    private EventDispatcher $EventDispatcher;

    public function __construct(
        private TemplateModel $TemplateModel
    ){
        $this->EventDispatcher = new EventDispatcher;
    }

    public function getFilePath(){
        $ThemeController = new ThemeController();
        return $ThemeController->getTemplateFilePath($this->TemplateModel->getName());
    }

    public function getUtilizedComponents():ComponentsIterator{
        $ComponentModels = [];
        foreach ($this->TemplateModel->getComponents() as $Registry) {
            array_push($ComponentModels,$Registry['model']);
        }
        $ComponentsIterator = new ComponentsIterator($ComponentModels);
        return $ComponentsIterator;
    }

    public function getTemplateName(){
        return $this->TemplateModel->getName();
    }

    public function registerComponent(ComponentModel $ComponentModel) {
        $this->TemplateModel->registerComponent($ComponentModel);
    }

    public function createTemplate(){

        $ThemeController = new ThemeController();
        $ThemeController->mount(AppSettings::getThemeName());

        $templatePath = $ThemeController->getTemplateFilePath($this->getTemplateName());
        if (file_exists($templatePath)) {
            throw new TemplateAlreadyExistsException($this->getTemplateName());
        }

        $templateContents = $this->EventDispatcher->dispatchEvent(OnCreateTemplateEvent::class,'');
        file_put_contents($templatePath,$templateContents ?? '');

    }

}
