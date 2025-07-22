<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders;

use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class StaticAssetBundler {

    public function __construct(
        private Finder $finder,
        private ThemeServiceInterface $themeService,
        private Filesystem $fileSystem
    ) {}

    public function bundleJsAssets(PageModel $pageModel): string {
        $assetsDir = $this->themeService->getAssetsDir(
            $pageModel->theme
        );
        $jsFiles = $this->finder
            ->files()
            ->in($assetsDir)
            ->depth('== 0') // Only top-level files, not subdirectories
            ->name('*.js');
        $bundledContent = "";
        foreach ($jsFiles as $file) {
            $bundledContent .= $this->fileSystem->readFile($file->getRealPath());
        }
        return $bundledContent;
    }

    public function bundleCssAssets(PageModel $pageModel): string {
        $assetsDir = $this->themeService->getAssetsDir(
            $pageModel->theme
        );
        $cssFiles = $this->finder
            ->files()
            ->in($assetsDir)
            ->depth('== 0') // Only top-level files, not subdirectories
            ->name('*.css');
        $bundledContent = "";
        foreach ($cssFiles as $file) {
            $bundledContent .= $this->fileSystem->readFile($file->getRealPath());
        }
        return $bundledContent;
    }

}