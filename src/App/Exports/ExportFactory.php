<?php 

namespace Kenjiefx\ScratchPHP\App\Exports;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Pages\PageService;

class ExportFactory {

    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly PageService $pageService
    ) {}

    public function createFromPage(
        PageModel $pageModel,
        string $content
    ): ExportModel {
        $pageDirFromRoot = $this->configuration->getRootDir() . $this->pageService::PAGES_DIR;
        $pageJsonPath = $pageModel->file->path;
        $relativePagePath = str_replace($pageDirFromRoot . '/', '', $pageJsonPath);
        $relativePath = preg_replace('/\.json$/i', '.html', $relativePagePath);
        return new ExportModel(
            $relativePath, $content
        );
    }

    public function createAsAsset(
        PageModel $pageModel,
        string $assetType,
        string $content
    ) {
        $pageDirFromRoot = $this->configuration->getRootDir() . $this->pageService::PAGES_DIR;
        $pageJsonPath = $pageModel->file->path;
        $relativePagePath = str_replace($pageDirFromRoot . '/', '', $pageJsonPath);
        $dir = dirname($relativePagePath);
        if ($this->configuration->useHashedFilenames()) {
            $fileName = $pageModel->id . '.' . $assetType;
        } else {
            $fileName = preg_replace('/\.json$/i', '.' . $assetType, basename($relativePagePath));
        }
        $relativePath = 'assets' 
            . ($dir !== '.' ? '/' . $dir : '') 
            . '/' . $fileName;
        return new ExportModel($relativePath, $content);
    }
    
    

}