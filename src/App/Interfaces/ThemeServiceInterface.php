<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

interface ThemeServiceInterface {

    public function getThemeDir(ThemeModel $themeModel): string;

    public function getAssetsDir(ThemeModel $themeModel): string;

    public function getTemplatesDir(ThemeModel $themeModel): string;

    public function getTemplatePath(ThemeModel $themeModel, TemplateModel $templateModel): string;

    public function getIndexPath(ThemeModel $themeModel): string;

    public function getComponentsDir(ThemeModel $themeModel): string;

    public function getComponentDir(ThemeModel $themeModel, ComponentModel $componentModel): string;

    public function getComponentJsPath(ThemeModel $themeModel, ComponentModel $componentModel): string;

    public function getComponentCssPath(ThemeModel $themeModel, ComponentModel $componentModel): string;

    public function getComponentHtmlPath(ThemeModel $themeModel, ComponentModel $componentModel): string;

    public function getBlocksDir(ThemeModel $themeModel): string;

    public function getBlockDir(ThemeModel $themeModel, BlockModel $blockModel): string;

    public function getBlockJsPath(ThemeModel $themeModel, BlockModel $blockModel): string;

    public function getBlockCssPath(ThemeModel $themeModel, BlockModel $blockModel): string;

    public function getBlockHtmlPath(ThemeModel $themeModel, BlockModel $blockModel): string;

    public function getSnippetsDir(ThemeModel $themeModel): string;
    
    public function getSnippetHtmlPath(ThemeModel $themeModel, string $snippetName): string;

}