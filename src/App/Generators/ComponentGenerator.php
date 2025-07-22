<?php

namespace Kenjiefx\ScratchPHP\App\Generators;

use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentCSSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentHTMLCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentJSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class ComponentGenerator {

    public function __construct(
        private ConfigurationInterface $configuration,
        private ComponentFactory $componentFactory,
        private ThemeServiceInterface $themeService,
        private DirectoryService $directoryService,
        private EventBus $eventBus,
        private Filesystem $filesystem
    ) {}

    public function generate(string $name) {
        $themeName = $this->configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $componentModel = $this->componentFactory->create($name, []);
        $componentDir = $this->themeService->getComponentDir($themeModel, $componentModel);
        if ($this->directoryService->exists($componentDir)) {
            $message = "Component already exists in path: " . $componentDir;
            throw new \Exception($message);
        }
        $this->directoryService->create($componentDir);
        $this->createComponentHtml($componentModel, $themeModel);
        $this->createComponentJs($componentModel, $themeModel);
        $this->createComponentCss($componentModel, $themeModel);
    }

    public function createComponentHtml(
        ComponentModel $componentModel,
        ThemeModel $themeModel
    ) {
        $event = new ComponentHTMLCreatedEvent($componentModel, "");
        $this->eventBus->dispatchEvent($event);
        $componentHtmlPath = $this->themeService->getComponentHtmlPath($themeModel, $componentModel);
        $this->filesystem->dumpFile($componentHtmlPath, $event->content);
    }

    public function createComponentJs(
        ComponentModel $componentModel,
        ThemeModel $themeModel
    ) {
        $event = new ComponentJSCreatedEvent($componentModel, "");
        $this->eventBus->dispatchEvent($event);
        $componentJsPath = $this->themeService->getComponentJsPath($themeModel, $componentModel);
        $this->filesystem->dumpFile($componentJsPath, $event->content);
    }

    public function createComponentCss(
        ComponentModel $componentModel,
        ThemeModel $themeModel
    ) {
        $event = new ComponentCSSCreatedEvent($componentModel, "");
        $this->eventBus->dispatchEvent($event);
        $componentCssPath = $this->themeService->getComponentCssPath($themeModel, $componentModel);
        $this->filesystem->dumpFile($componentCssPath, $event->content);
    }

}