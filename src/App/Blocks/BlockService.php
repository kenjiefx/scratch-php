<?php

namespace Kenjiefx\ScratchPHP\App\Blocks;

use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class BlockService
{
    public const BLOCK_DIR = '/blocks';

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly FileFactory $fileFactory
    ) {}

    /**
     * Extracts the block name from its namespace.
     */
    public function getName(BlockModel $blockModel): string
    {
        return basename(str_replace('\\', '/', $blockModel->namespace));
    }

    /**
     * Returns the base directory path where blocks are stored for the given theme.
     */
    public function getBlocksDir(ThemeModel $theme): string
    {
        return rtrim($this->themeService->getThemeDir($theme), DIRECTORY_SEPARATOR) . self::BLOCK_DIR;
    }

    /**
     * Returns the directory path of a specific block within the theme.
     */
    public function getBlockDir(BlockModel $blockModel, ThemeModel $theme): string
    {
        return $this->getBlocksDir($theme) . DIRECTORY_SEPARATOR . ltrim($blockModel->namespace, DIRECTORY_SEPARATOR);
    }

    /**
     * Returns a File object pointing to the component file with the given extension.
     */
    private function getBlockFile(BlockModel $blockModel, ThemeModel $theme, string $extension): File
    {
        $dir = $this->getBlockDir($blockModel, $theme);
        $name = $this->getName($blockModel);
        return $this->fileFactory->create("{$dir}/{$name}.{$extension}");
    }

    public function getJsonPath(BlockModel $blockModel, ThemeModel $theme): File
    {
        return $this->getBlockFile($blockModel, $theme, 'json');
    }

    public function getHtmlPath(BlockModel $blockModel, ThemeModel $theme): File
    {
        return $this->getBlockFile($blockModel, $theme, 'php');
    }

    public function getCssPath(BlockModel $blockModel, ThemeModel $theme): File
    {
        return $this->getBlockFile($blockModel, $theme, 'css');
    }

    public function getJsPath(BlockModel $blockModel, ThemeModel $theme): File
    {
        return $this->getBlockFile($blockModel, $theme, 'js');
    }
}
