<?php

namespace Kenjiefx\ScratchPHP\App\Components;

use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Files\FileFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;

class ComponentService
{
    public const COMPONENT_DIR = '/components';

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly FileFactory $fileFactory
    ) {}

    /**
     * Extracts the component name from its namespace.
     */
    public function getName(ComponentModel $component): string
    {
        return basename(str_replace('\\', '/', $component->namespace));
    }

    /**
     * Returns the base directory path where components are stored for the given theme.
     */
    public function getComponentsDir(ThemeModel $theme): string
    {
        return rtrim($this->themeService->getThemeDir($theme), DIRECTORY_SEPARATOR) . self::COMPONENT_DIR;
    }

    /**
     * Returns the directory path of a specific component within the theme.
     */
    public function getComponentDir(ComponentModel $component, ThemeModel $theme): string
    {
        return $this->getComponentsDir($theme) . DIRECTORY_SEPARATOR . ltrim($component->namespace, DIRECTORY_SEPARATOR);
    }

    /**
     * Returns a File object pointing to the component file with the given extension.
     */
    private function getComponentFile(ComponentModel $component, ThemeModel $theme, string $extension): File
    {
        $dir = $this->getComponentDir($component, $theme);
        $name = $this->getName($component);
        return $this->fileFactory->create("{$dir}/{$name}.{$extension}");
    }

    public function getJsonPath(ComponentModel $component, ThemeModel $theme): File
    {
        return $this->getComponentFile($component, $theme, 'json');
    }

    public function getHtmlPath(ComponentModel $component, ThemeModel $theme): File
    {
        return $this->getComponentFile($component, $theme, 'php');
    }

    public function getCssPath(ComponentModel $component, ThemeModel $theme): File
    {
        return $this->getComponentFile($component, $theme, 'css');
    }

    public function getJsPath(ComponentModel $component, ThemeModel $theme): File
    {
        return $this->getComponentFile($component, $theme, 'js');
    }
}
