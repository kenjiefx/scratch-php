<?php

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class EditorThemeService extends ThemeService {

    public function getTemplatePath(ThemeModel $themeModel, TemplateModel $templateModel): string {
        return __DIR__ . '/theme/template.php';
    }

}