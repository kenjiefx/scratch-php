<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

use Kenjiefx\ScratchPHP\App\Builders\BuildService;
use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageFactory;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Pages\PageService;

class EditorController {

    public function __construct(
        public readonly BuildService $buildService,
        public readonly PageFactory $pageFactory,
        public readonly PageService $pageService,
        public readonly ConfigurationInterface $configuration,
        public readonly ComponentFactory $componentFactory,
        public readonly EditorService $editorService
    ){

    }

    public function render(array $params): string {
        if (!isset($params['component'])) {
            throw new \Exception("Component parameter is required for rendering the editor.");
        }
        $component = str_replace('.', '/', $params['component']);
        $componentModel = $this->componentFactory->create($component);
        $this->editorService->setComponentModel($componentModel);
        $rootDir = $this->configuration->getRootDir();
        $pageDir = $rootDir . $this->pageService::PAGES_DIR;
        $pageJsonPath = $pageDir . '/index.json'; // Default to index.json for the editor
        $pageModel = $this->pageFactory->create($pageJsonPath);
        $pageRegistry = new PageRegistry([$pageModel]);
        $this->buildService->build($pageRegistry);
        $htmlPath = __DIR__ . '/exports/page.html';
        if (!file_exists($htmlPath)) {
            throw new \Exception("HTML file not found: $htmlPath");
        }
        return file_get_contents($htmlPath);
    }

    public function retrieveAsset(string $assetName){
        $assetPath = __DIR__ . '/exports/';
        // Check if assetName is for css or js
        if (pathinfo($assetName, PATHINFO_EXTENSION) === 'css') {
            $assetPath .= 'style.css';
        } elseif (pathinfo($assetName, PATHINFO_EXTENSION) === 'js') {
            $assetPath .= 'script.js';
        } else {
            $assetPath .= $assetName;
        }
        if (!file_exists($assetPath)) {
            throw new \Exception("Asset file not found: $assetPath");
        }
        return file_get_contents($assetPath);
    }

}