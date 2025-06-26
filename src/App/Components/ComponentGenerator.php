<?php 

namespace Kenjiefx\ScratchPHP\App\Components;

use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Events\ComponentCSSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\ComponentHTMLCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\ComponentJSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class ComponentGenerator {

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly ComponentService $componentService,
        public readonly ComponentFactory $componentFactory,
        public readonly DirectoryService $directoryService,
        public readonly EventDispatcher $eventDispatcher,
        public readonly FileService $fileService,
        public readonly FileFactory $fileFactory
    ) {}

    public function generate(
        string $namespace,
        ThemeModel $themeModel
    ){
        $componentModel = $this->componentFactory->create($namespace);
        $componentDir = $this->componentService->getComponentDir($componentModel, $themeModel);
        if ($this->directoryService->exists($componentDir)) {
            $message = "Component already exists in path: " . $componentDir;
            throw new \Exception($message);
        }
        $this->directoryService->create($componentDir);
        $this->createHtml($componentModel, $themeModel, $componentDir);
        $this->createCss($componentModel, $themeModel, $componentDir);
        $this->createJs($componentModel, $themeModel, $componentDir);
    }

    public function createHtml(
        ComponentModel $componentModel,
        ThemeModel $themeModel,
        string $componentDir
    ){
        $content = "";
        $event = new ComponentHTMLCreatedEvent(
            $componentModel,
            $componentDir,
            $content
        );
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->componentService->getHtmlPath($componentModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }

    public function createCss(
        ComponentModel $componentModel,
        ThemeModel $themeModel,
        string $componentDir
    ){
        $content = "";
        $event = new ComponentCSSCreatedEvent(
            $componentModel,
            $componentDir,
            $content
        );
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->componentService->getCssPath($componentModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }

    public function createJs(
        ComponentModel $componentModel,
        ThemeModel $themeModel,
        string $componentDir
    ){
        $content = "";
        $event = new ComponentJSCreatedEvent(
            $componentModel,
            $componentDir,
            $content
        );
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->componentService->getJsPath($componentModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }


}