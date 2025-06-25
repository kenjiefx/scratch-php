<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Files\FileService;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class AssetCollector {

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly FileService $fileService,
        public readonly FileFactory $fileFactory
    ) {}

    /**
     * Collects the assets from the theme's assets directory.
     */
    public function collectAll(
        AssetsEnum $assetType,
        ThemeModel $themeModel
    ){
        // Return empty content if asset type is not collectable 
        if (!$this->isCollectible($assetType)) {
            return '';
        }
        $content = '';
        $paths = $this->getPathsToAssetFiles($assetType, $themeModel);
        foreach ($paths as $path) {
            $file = $this->fileFactory->create($path);
            // Check if the file exists and is readable
            if ($this->fileService->fileExists($file)) {
                // Append the content of the file to the collected content
                $content .= $this->fileService->readFile($file);
            }
        }
        return $content;
    }

    /**
     * At this time, only CSS and JS assets are considered collectable.
     */
    public function isCollectible(
        AssetsEnum $assetType
    ): bool {
        // Check if the asset type is collectable
        return match ($assetType) {
            AssetsEnum::CSS, 
            AssetsEnum::JS => true,
            default => false,
        };
    }

    /**
     * Returns an array of paths to the asset files of the specified type.
     */
    public function getPathsToAssetFiles(
        AssetsEnum $assetType,
        ThemeModel $themeModel
    ){
        // Get the assets directory path
        $assetsDirPath = $this->themeService->getAssetsDir($themeModel);
        
        // Return an array of paths to the asset files
        return match ($assetType) {
            AssetsEnum::CSS => glob($assetsDirPath . '/*.css'),
            AssetsEnum::JS => glob($assetsDirPath . '/*.js'),
            default => [],
        };
    }

}