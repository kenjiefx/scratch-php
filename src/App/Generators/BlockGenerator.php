<?php

namespace Kenjiefx\ScratchPHP\App\Generators;

use Kenjiefx\ScratchPHP\App\Blocks\BlockFactory;
use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\BlockCSSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\BlockHTMLCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\Instances\BlockJSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class BlockGenerator {

    public function __construct(
        private ConfigurationInterface $configuration,
        private BlockFactory $blockFactory,
        private ThemeServiceInterface $themeService,
        private DirectoryService $directoryService,
        private EventBus $eventBus,
        private Filesystem $filesystem
    ) {}

    public function generate(string $name) {
        $themeName = $this->configuration->getThemeName();
        $themeModel = new ThemeModel($themeName);
        $blockModel = $this->blockFactory->create($name, []);
        $blockDir = $this->themeService->getBlockDir($themeModel, $blockModel);
        if ($this->directoryService->exists($blockDir)) {
            $message = "Block already exists in path: " . $blockDir;
            throw new \Exception($message);
        }
        $this->directoryService->create($blockDir);
        $this->createBlockHtml($blockModel, $themeModel);
        $this->createBlockJs($blockModel, $themeModel);
        $this->createBlockCss($blockModel, $themeModel);
    }

    public function createBlockHtml(
        BlockModel $blockModel,
        ThemeModel $themeModel
    ) {
        $event = new BlockHTMLCreatedEvent($blockModel, "");
        $this->eventBus->dispatchEvent($event);
        $htmlPath = $this->themeService->getBlockHtmlPath($themeModel, $blockModel);
        $this->filesystem->dumpFile($htmlPath, $event->content);
    }

    public function createBlockJs(
        BlockModel $blockModel,
        ThemeModel $themeModel
    ) {
        $event = new BlockJSCreatedEvent($blockModel, "");
        $this->eventBus->dispatchEvent($event);
        $jsPath = $this->themeService->getBlockJsPath($themeModel, $blockModel);
        $this->filesystem->dumpFile($jsPath, $event->content);
    }

    public function createBlockCss(
        BlockModel $blockModel,
        ThemeModel $themeModel
    ) {
        $event = new BlockCSSCreatedEvent($blockModel, "");
        $this->eventBus->dispatchEvent($event);
        $cssPath = $this->themeService->getBlockCssPath($themeModel, $blockModel);
        $this->filesystem->dumpFile($cssPath, $event->content);
    }

}