<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateController
{

    public function __construct(
        private ThemeController $themeController
    ){
        AppSettings::load();
    }

    public function createTemplate(
        string $templateName
    ){
        $this->themeController->useTheme(AppSettings::getThemeName());
        $templatePath = $this->themeController->getTemplatePath($templateName);
        if (file_exists($templatePath)) {
            $error = 'Template Already Exists! The template you are trying to create named "';
            $error .= $templateName.'" already exists in the theme.';
            throw new \Exception($error);
        }
        $templateHTML = '';
        foreach (AppSettings::extensions()->getExtensions() as $extension) {
            if (method_exists($extension,'onCreateTemplate')) {
                $templateHTML .= $extension->onCreateTemplate($templateHTML);
            }
        }
        file_put_contents($templatePath,$templateHTML);
    }

}
