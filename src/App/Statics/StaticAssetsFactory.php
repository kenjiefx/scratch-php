<?php 

namespace Kenjiefx\ScratchPHP\App\Statics;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class StaticAssetsFactory
{

    public function __construct(
        public readonly ConfigurationInterface $configurationInterface,
        public readonly ThemeFactory $themeFactory,
        public readonly ThemeService $themeService,
        public readonly FileFactory $fileFactory
    ) {}

    /**
     * Creates a static asset model.
     *
     * @param string $fileName The filename of the asset.
     * @return StaticAssetsModel
     */
    public function create(string $fileName): StaticAssetsModel
    {
        $themeDir = $this->getThemeDir();
        $themePath = "{$themeDir}/assets/{$fileName}";
        $fileObject = $this->fileFactory->create($themePath);
        return new StaticAssetsModel($fileName, $fileObject);
    }

    public function getThemeDir() {
        $themeName = $this->configurationInterface->getThemeName();
        $themeModel = $this->themeFactory->create($themeName);
        return $this->themeService->getThemeDir($themeModel);
    }
}