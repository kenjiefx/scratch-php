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
        public readonly ComponentModel $ComponentModel
    ){
        $this->ThemeController = ContainerFactory::create()->get(ThemeController::class);
    }

    public function getdir(): string {
        return $this->ThemeController->path()->components 
            . $this->ComponentModel->name 
            . '/';
    }

    public function paths(): ComponentPaths {
        return new ComponentPaths(
            $this->getdir(),
            $this->ComponentModel->name
        );
    }

    public function create(array $options){

        $this->ThemeController->mount(AppSettings::getThemeName());
        $dirPath = $this->getdir();

        if (is_dir($dirPath)) {
            throw new ComponentAlreadyExistsException($dirPath);
        }

        $ComponentHtmlDTO = new ComponentEventDTO($this);
        $ComponentCssDTO  = new ComponentEventDTO($this);
        $ComponentJsDTO   = new ComponentEventDTO($this);

        if ($options['applyExtensions']) {
            $EventDispatcher = new EventDispatcher;
            $EventDispatcher->dispatchEvent(
                OnCreateComponentHtmlEvent::class,
                $ComponentHtmlDTO
            );
            $EventDispatcher->dispatchEvent(
                OnCreateComponentCssEvent::class,
                $ComponentCssDTO
            );
            $EventDispatcher->dispatchEvent(
                OnCreateComponentJsEvent::class,
                $ComponentJsDTO
            );
        }
    
        mkdir($dirPath);

        file_put_contents(
            $this->paths()->html(),
            $ComponentHtmlDTO->content
        );

        file_put_contents(
            $this->paths()->css(),
            $ComponentCssDTO->content
        );

        $usets = $options['useTypeScript'] ?? false;

        if ($usets) {
            file_put_contents(
                $this->paths()->ts(),
                $ComponentJsDTO->content
            );
        } else {
            file_put_contents(
                $this->paths()->js(),
                $ComponentJsDTO->content
            );
        }

    }
}
