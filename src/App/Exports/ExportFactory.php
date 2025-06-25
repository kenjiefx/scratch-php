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
        // Example: <root>/pages
        $pageDirFromRoot = $this->configuration->getRootDir() . $this->pageService::PAGES_DIR;
        // Example: <root>/pages/admin/dashboard.json
        $pageJsonPath = $pageModel->file->path;
        // Remove the root pages directory prefix from the JSON file path
        $relativePath = str_replace($pageDirFromRoot . DIRECTORY_SEPARATOR, '', $pageJsonPath);
        // Replace .json extension with .html
        $relativePath = preg_replace('/\.json$/i', '.html', $relativePath);
        // Replative path should be: admin/dashboard.html
        return new ExportModel(
            $relativePath, $content
        );
    }

    public function createAsAsset(
        PageModel $pageModel,
        string $assetType,
        string $content
    ) {
        // Example: <root>/pages
        $pageDirFromRoot = $this->configuration->getRootDir() . $this->pageService::PAGES_DIR;
        // Example: <root>/pages/admin/analytics/dashboard.json
        $pageJsonPath = $pageModel->file->path;
        // Step 1: Get the path relative to <root>/pages/
        $relativePagePath = str_replace($pageDirFromRoot . '/', '', $pageJsonPath);
        // Step 2: Extract the directory (e.g. admin/analytics)
        $dir = dirname($relativePagePath);
        if ($this->configuration->useHashedFilenames()) {
            // Use the hashed file name (page ID)
            $fileName = $pageModel->id . '.' . $assetType;
        } else {
            // Use the original filename with the asset extension
            $fileName = preg_replace('/\.json$/i', '.' . $assetType, basename($relativePagePath));
        }
        // Step 3: Build the final relative path
        $relativePath = 'assets' 
            . ($dir !== '.' ? '/' . $dir : '') 
            . '/' . $fileName;
        return new ExportModel($relativePath, $content);
    }
    
    

}