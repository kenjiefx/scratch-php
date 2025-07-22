<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

interface ExportServiceInterface {

    public function exportPageContents(PageContent $pageContent, PageModel $pageModel): void;

    public function exportStaticAssets(StaticAssetRegistry $staticAssetRegistry, PageModel $pageModel): void;

    public function createPageAssetPath(PageModel $pageModel, string $assetType): string;

}