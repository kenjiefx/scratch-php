<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Exporter;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class HttpResourceExportService implements ExportServiceInterface {

    public function __construct(
        private Filesystem $filesystem,
        private ThemeServiceInterface $themeService,
        private DirectoryService $directoryService
    ) {}

    public function exportPageContents(PageContent $pageContent, PageModel $pageModel): void {
        $exportsDir = $this->getExportsDir();
        $html = $pageContent->html;
        $this->filesystem->dumpFile("{$exportsDir}/page.html", $html);
        $css = $pageContent->css;
        $this->filesystem->dumpFile("{$exportsDir}/page.css", $css);
        $javascript = $pageContent->javascript;
        $this->filesystem->dumpFile("{$exportsDir}/page.js", $javascript);
    }

    public function exportStaticAssets(StaticAssetRegistry $registry, PageModel $pageModel): void {
        $assetsDir = $this->themeService->getAssetsDir($pageModel->theme);
        $exportsDir = $this->getExportsDir();
        foreach ($registry->getAll() as $staticAssetModel) {
            $fileName = $staticAssetModel->fileName;
            $assetFilePath = "{$assetsDir}/{$fileName}";
            $exportFilePath = "{$exportsDir}/{$fileName}";
            if (!$this->filesystem->exists($assetFilePath)) {
                throw new \Exception("Asset file does not exist: {$assetFilePath}");
            }
            $this->filesystem->copy(
                $assetFilePath, $exportFilePath, true
            );
        }
    }

    public function createPageAssetPath(PageModel $pageModel, string $assetType): string {
        // Implement logic to create the asset path based on the page model and asset type
        return "assets/page.{$assetType}";
    }

    public function getExportsDir() {
        return __DIR__ . "/exports";
    }

}