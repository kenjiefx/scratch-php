<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\DistExporter;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use Symfony\Component\Filesystem\Filesystem;

class DistExportService implements ExportServiceInterface {

    public function __construct(
        private ConfigurationInterface $configuration,
        private DirectoryService $directoryService,
        private Filesystem $filesystem,
        private ThemeServiceInterface $themeService,
    ) {}

    public function exportPageContents(PageContent $pageContent, PageModel $pageModel): void {
        $this->exportPageHtml($pageModel, $pageContent->html);
        $this->exportPageCss($pageModel, $pageContent->css);
        $this->exportPageJs($pageModel, $pageContent->javascript);
    }

    public function exportPageHtml(PageModel $pageModel, string $htmlContent): void {
        $distDir = $this->joinPath(
            $this->configuration->getRootDir(),
            $this->configuration->getExportDir()
        );
        $pageHtmlUrl = $pageModel->urlPath;
        $distHtmlPath = $this->joinPath(
            $distDir,
            $pageHtmlUrl
        );
        $this->createDirOfPath($distHtmlPath);
        $this->filesystem->dumpFile($distHtmlPath, $htmlContent);
    }

    public function exportPageCss(PageModel $pageModel, string $cssContent): void {
        $distDir = $this->joinPath(
            $this->configuration->getRootDir(),
            $this->configuration->getExportDir()
        );
        $cssAssetPath = $this->createPageAssetPath($pageModel, 'css');
        $distCssPath = $this->joinPath(
            $distDir,
            $cssAssetPath
        );
        $this->createDirOfPath($distCssPath);
        $this->filesystem->dumpFile($distCssPath, $cssContent);
    }

    public function exportPageJs(PageModel $pageModel, string $javascriptContent): void {
        $distDir = $this->joinPath(
            $this->configuration->getRootDir(),
            $this->configuration->getExportDir()
        );
        $jsAssetPath = $this->createPageAssetPath($pageModel, 'js');
        $distJsPath = $this->joinPath(
            $distDir,
            $jsAssetPath
        );
        $this->createDirOfPath($distJsPath);
        $this->filesystem->dumpFile($distJsPath, $javascriptContent);
    }

    public function joinPath(string $path1, string $path2): string {
        return rtrim($path1, '/') . '/' . ltrim($path2, '/');
    }

    /**
     * Creates a directory if it does not exist, 
     * given a path to a file.
     *
     * @param string $filePath The path to the file for which the directory should be created.
     */
    public function createDirOfPath(string $filePath): void {
        $dirOfFile = dirname($filePath);
        if ($this->directoryService->isDirectory($dirOfFile)) {
            return;
        }
        $this->directoryService->create($dirOfFile);
    }

    public function exportStaticAssets(StaticAssetRegistry $registry, PageModel $pageModel): void {
        $assetsDir = $this->themeService->getAssetsDir($pageModel->theme);
        $assetsExportDir = $this->configuration->getExportDir() . '/assets';
        foreach ($registry->getAll() as $staticAssetModel) {
            $fileName = $staticAssetModel->fileName;
            $assetFilePath = "{$assetsDir}/{$fileName}";
            $exportFilePath = "{$assetsExportDir}/{$fileName}";
            if (!$this->filesystem->exists($assetFilePath)) {
                throw new \Exception("Asset file does not exist: {$assetFilePath}");
            }
            $this->filesystem->copy(
                $assetFilePath, $exportFilePath, true
            );
        }
    }

    public function createPageAssetPath(PageModel $pageModel, string $assetType): string {
        $pageUrl = $pageModel->urlPath;
        if ($this->configuration->useHashedFilenames()) {
            $pageUrl = $this->replaceFilenameWithPageId($pageUrl, $pageModel->id);
        }
        $pageUrl = preg_replace('/\.html$/i', '', $pageUrl);
        $assetPath = '/assets/' . $pageUrl . '.' . $assetType;
        $assetPath = str_replace('//', '/', $assetPath);
        return $assetPath;
    }

    public function replaceFilenameWithPageId(string $pageUrl, string $pageId): string {
        $fileNameWithExt = basename($pageUrl);
        $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        return str_replace(
            "{$fileName}.html",
            "{$pageId}.html",
            $pageUrl
        );
    }

}