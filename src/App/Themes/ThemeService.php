<?php 

namespace Kenjiefx\ScratchPHP\App\Themes;

use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;


class ThemeService {

    const THEME_DIR = '/theme';

    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly FileFactory $fileFactory
    ) {}

    public function getThemeIndexPath(
        ThemeModel $themeModel
    ): File
    {
        $filePath = $this->getThemeDir($themeModel) . '/index.php';
        return $this->fileFactory->create($filePath);
    }

    public function getThemeDir(
        ThemeModel $themeModel
    ): string
    {
        $themeName = $themeModel->name;
        return $this->configuration->getRootDir() . self::THEME_DIR . '/' . $themeName;
    }

    public function getAssetsDir(
        ThemeModel $themeModel
    ): string
    {
        return $this->getThemeDir($themeModel) . '/assets';
    }

}