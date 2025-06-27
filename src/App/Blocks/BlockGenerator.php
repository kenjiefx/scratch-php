<?php 

namespace Kenjiefx\ScratchPHP\App\Blocks;

use Kenjiefx\ScratchPHP\App\Directories\DirectoryService;
use Kenjiefx\ScratchPHP\App\Events\BlockCSSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\BlockHTMLCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\BlockJSCreatedEvent;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class BlockGenerator {

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly BlockService $blockService,
        public readonly BlockFactory $blockFactory,
        public readonly DirectoryService $directoryService,
        public readonly EventDispatcher $eventDispatcher,
        public readonly FileService $fileService,
        public readonly FileFactory $fileFactory
    ) {}

    public function generate(
        string $namespace,
        ThemeModel $themeModel
    ){
        $blockModel = $this->blockFactory->create($namespace);
        $blockDir = $this->blockService->getBlockDir($blockModel, $themeModel);
        if ($this->directoryService->exists($blockDir)) {
            $message = "Block already exists in path: " . $blockDir;
            throw new \Exception($message);
        }
        $this->directoryService->create($blockDir);
        $this->createHtml($blockModel, $themeModel, $blockDir);
        $this->createCss($blockModel, $themeModel, $blockDir);
        $this->createJs($blockModel, $themeModel, $blockDir);
    }

    public function createHtml(
        BlockModel $blockModel,
        ThemeModel $themeModel,
        string $blockDir
    ){
        $content = "";
        $event = new BlockHTMLCreatedEvent([
            'blockModel' => $blockModel,
            'blockDir' => $blockDir,
            'content' => $content
        ]);
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->blockService->getHtmlPath($blockModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }

    public function createCss(
        BlockModel $blockModel,
        ThemeModel $themeModel,
        string $blockDir
    ){
        $content = "";
        $event = new BlockCSSCreatedEvent([
            'blockModel' => $blockModel,
            'blockDir' => $blockDir,
            'content' => $content
        ]);
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->blockService->getCssPath($blockModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }

    public function createJs(
        BlockModel $blockModel,
        ThemeModel $themeModel,
        string $blockDir
    ){
        $content = "";
        $event = new BlockJSCreatedEvent([
            'blockModel' => $blockModel,
            'blockDir' => $blockDir,
            'content' => $content
        ]);
        $this->eventDispatcher->dispatchEvent($event);
        $content = $event->getContent();
        $file = $this->blockService->getJsPath($blockModel, $themeModel);
        $this->fileService->writeFile($file, $content);
    }


}