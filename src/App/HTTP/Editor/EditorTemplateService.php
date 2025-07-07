<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class EditorTemplateService implements TemplateServiceInterface {

    /**
     * Returns the directory path where templates are stored for a specific theme.
     * @return string
     */
    public function getTemplatesDir(
        ThemeModel $themeModel
    ): string {
        return '';
    }

    /**
     * Returns the path to a specific template file.
     * @return string
     */
    public function getTemplatePath(
        ThemeModel $themeModel,
        TemplateModel $templateModel
    ): File {
        return new File(__DIR__ . '/editor.php');
    }

}