<?php

namespace Kenjiefx\ScratchPHP\App\Implementations\ThemeManager;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class ThemeService implements ThemeServiceInterface {

    public function __construct(
        private ConfigurationInterface $configuration
    ) {}

    public function getThemeDir(ThemeModel $themeModel): string {
        $dir = $this->getThemesDir() . DIRECTORY_SEPARATOR . $themeModel->name;
        return $this->normalizePath($dir);
    }

    public function getThemesDir(): string {
        $dir = $this->configuration->getRootDir() . DIRECTORY_SEPARATOR . 'theme';
        return $this->normalizePath($dir);
    }

    public function getAssetsDir(ThemeModel $themeModel): string {
        $dir = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'assets';
        return $this->normalizePath($dir);
    }

    public function getTemplatesDir(ThemeModel $themeModel): string {
        $dir = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'templates';
        return $this->normalizePath($dir);
    }

    public function getTemplatePath(ThemeModel $themeModel, TemplateModel $templateModel): string {
        $path = $this->getTemplatesDir($themeModel) . DIRECTORY_SEPARATOR . $templateModel->name . '.php';
        return $this->normalizePath($path);
    }

    public function getIndexPath(ThemeModel $themeModel): string {
        $path = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'index.php';
        return $this->normalizePath($path);
    }

    public function getComponentsDir(ThemeModel $themeModel): string {
        $dir = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'components';
        return $this->normalizePath($dir);
    }

    public function getComponentDir(ThemeModel $themeModel, ComponentModel $componentModel): string {
        $dir = $this->getComponentsDir($themeModel) . DIRECTORY_SEPARATOR . $componentModel->name;
        return $this->normalizePath($dir);
    }
    
    public function getComponentJsPath(ThemeModel $themeModel, ComponentModel $componentModel): string {
        $basename = basename(str_replace('\\', '/', $componentModel->name));
        $path = $this->getComponentDir($themeModel, $componentModel) . DIRECTORY_SEPARATOR . $basename . '.js';
        return $this->normalizePath($path);
    }

    public function getComponentCssPath(ThemeModel $themeModel, ComponentModel $componentModel): string {
        $basename = basename(str_replace('\\', '/', $componentModel->name));
        $path = $this->getComponentDir($themeModel, $componentModel) . DIRECTORY_SEPARATOR . $basename . '.css';
        return $this->normalizePath($path);
    }

    public function getComponentHtmlPath(ThemeModel $themeModel, ComponentModel $componentModel): string {
        $basename = basename(str_replace('\\', '/', $componentModel->name));
        $path = $this->getComponentDir($themeModel, $componentModel) . DIRECTORY_SEPARATOR . $basename . '.php';
        return $this->normalizePath($path);
    }
    
    public function getBlocksDir(ThemeModel $themeModel): string {
        $dir = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'blocks';
        return $this->normalizePath($dir);
    }

    public function getBlockDir(ThemeModel $themeModel, BlockModel $blockModel): string {
        $dir = $this->getBlocksDir($themeModel) . DIRECTORY_SEPARATOR . $blockModel->name;
        return $this->normalizePath($dir);
    }

    public function getBlockJsPath(ThemeModel $themeModel, BlockModel $blockModel): string {
        $basename = basename(str_replace('\\', '/', $blockModel->name));
        $path = $this->getBlockDir($themeModel, $blockModel) . DIRECTORY_SEPARATOR . $basename . '.js';
        return $this->normalizePath($path);
    }

    public function getBlockCssPath(ThemeModel $themeModel, BlockModel $blockModel): string {
        $basename = basename(str_replace('\\', '/', $blockModel->name));
        $path = $this->getBlockDir($themeModel, $blockModel) . DIRECTORY_SEPARATOR . $basename . '.css';
        return $this->normalizePath($path);
    }

    public function getBlockHtmlPath(ThemeModel $themeModel, BlockModel $blockModel): string {
        $basename = basename(str_replace('\\', '/', $blockModel->name));
        $path = $this->getBlockDir($themeModel, $blockModel) . DIRECTORY_SEPARATOR . $basename . '.php';
        return $this->normalizePath($path);
    }

    public function getSnippetsDir(ThemeModel $themeModel): string {
        $dir = $this->getThemeDir($themeModel) . DIRECTORY_SEPARATOR . 'snippets';
        return $this->normalizePath($dir);
    }

    public function getSnippetHtmlPath(ThemeModel $themeModel, string $snippetName): string {
        $path = $this->getSnippetsDir($themeModel) . DIRECTORY_SEPARATOR . $snippetName . '.php';
        return $this->normalizePath($path);
    }

    public function normalizePath(string $path): string {
        return str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
    }

}