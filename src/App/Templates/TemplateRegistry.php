<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateRegistry
{

    private static array $templateModels;

    public function __construct(
        private ThemeController $themeController
    )
    {
        if (!isset(static::$templateModels)) {
            static::$templateModels = [];
        }
    }

    public function register(
        string $templateName
    ){
        $templatePath = $this->themeController->getTemplatePath($templateName);
        if (!file_exists($templatePath)) {
            $error = 'Template Not Found! Attempt to use the template name "'.$templateName.'" ';
            $error .= 'when it is not found in the theme with the path: '.$templatePath;
            throw new \Exception($error);
        }
        if (!isset(static::$templateModels[$templateName])) {
            $templateId = time().count(static::$templateModels);
            static::$templateModels[$templateName] = new TemplateModel(
                id: $templateId,
                name: $templateName,
                templatePath: $templatePath
            );
        }
        return static::$templateModels[$templateName];
    }

    public function getTemplatePath(
        string $templateName
    ){
        if (isset(static::$templateModels[$templateName])) {
            return static::$templateModels[$templateName]->getTemplatePath();
        }
        return null;
    }

    public function getTemplateModel(
        string $templateName
    ){
        return static::$templateModels[$templateName];
    }
}
