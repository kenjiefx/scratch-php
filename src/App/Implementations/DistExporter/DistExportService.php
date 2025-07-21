<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\DistExporter;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

class DistExportService implements ExportServiceInterface {

    public function __construct(

    ) {}

    public function exportPageContents(PageContent $pageContent, PageModel $pageModel): void {

    }

    public function exportStaticAssets(StaticAssetRegistry $staticAssetRegistry, PageModel $pageModel): void {
        
    }

    public function createPageAssetPath(PageModel $pageModel, string $assetType): string {
        $pageUrl = $pageModel->urlPath;
        // Remove .html from page path
        $pageUrl = preg_replace('/\.html$/i', '', $pageUrl);
        // Append asset type
        $assetPath = '/assets/' . $pageUrl . '.' . $assetType;
        // Remove double slashes if they exist
        $assetPath = str_replace('//', '/', $assetPath);
        return $assetPath;
    }

}