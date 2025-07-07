<?php 

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

interface TemplateServiceInterface {

    /**
     * Returns the directory path where templates are stored for a specific theme.
     * @return string
     */
    public function getTemplatesDir(
        ThemeModel $themeModel
    ): string;

    /**
     * Returns the path to a specific template file.
     * @return string
     */
    public function getTemplatePath(
        ThemeModel $themeModel,
        TemplateModel $templateModel
    ): File;

}