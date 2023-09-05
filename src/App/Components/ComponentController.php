<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentCssEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Exceptions\ComponentAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentController
{
    private ThemeController $ThemeController;

    public function __construct(
        private ComponentModel $ComponentModel
    ){
        $this->ThemeController = ContainerFactory::create()->get(ThemeController::class);
    }

    public function setComponent(ComponentModel $ComponentModel){
        $this->ComponentModel = $ComponentModel;
    }

    public function getComponent():ComponentModel {
        return $this->ComponentModel;
    }

    public function getDirPath(){
        return $this->ThemeController->getComponentsDirPath($this->ComponentModel->getName());
    }

    public function getHtmlPath(){
        return $this->getDirPath().'/'.$this->ComponentModel->getName().'.php';
    }

    public function createComponent (array $options){

        $this->ThemeController->mount(AppSettings::getThemeName());
        $dirPath = $this->getDirPath();

        if (is_dir($dirPath)) {
            throw new ComponentAlreadyExistsException($dirPath);
        }

        $jsFileType = ($options['useTypeScript']) ? '.ts' : '.js';

        $htmlPath = $dirPath.'/'.$this->ComponentModel->getName().'.php';
        $jsPath   = $dirPath.'/'.$this->ComponentModel->getName().$jsFileType;
        $cssPath  = $dirPath.'/'.$this->ComponentModel->getName().'.css';

        $this->ComponentModel->setHtml('');
        $this->ComponentModel->setCss('');
        $this->ComponentModel->setJavascript('');

        if ($options['applyExtensions']) {
            $EventDispatcher = new EventDispatcher;
            $html = $EventDispatcher->dispatchEvent(OnCreateComponentHtmlEvent::class,$this);
            $css  = $EventDispatcher->dispatchEvent(OnCreateComponentCssEvent::class,$this);
            $js   = $EventDispatcher->dispatchEvent(OnCreateComponentJsEvent::class,$this);
        }
        

        mkdir($dirPath);

        file_put_contents($htmlPath,$this->ComponentModel->getHtml());
        file_put_contents($cssPath,$this->ComponentModel->getCss());
        file_put_contents($jsPath,$this->ComponentModel->getJavascript());

    }
}
