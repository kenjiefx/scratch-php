<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Exceptions\TemplateNotFoundException;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateRegistry
{

    private static array $array_of_template_models;

    public function __construct(
        private ThemeController $ThemeController
    )
    {
        if (!isset(static::$array_of_template_models)) {
            static::$array_of_template_models = [];
        }
    }

    public function register(string $template_name):TemplateModel{

        # Validating whether the template exists in the theme
        $this->validate_if_template_exist($template_name);

        # Generates the template path
        $template_path = $this->get_template_path($template_name);

        if (!isset(static::$array_of_template_models[$template_name])) {
            $template_id = time().count(static::$array_of_template_models);
            static::$array_of_template_models[$template_name] = new TemplateModel(
                id: $template_id,
                name: $template_name,
                template_path: $template_path
            );
        }
        return static::$array_of_template_models[$template_name];
    }

    /**
     * Validates if the template exists in the theme
     */
    public function validate_if_template_exist(string $template_name){
        $template_path = $this->get_template_path($template_name);
        if (!file_exists($template_path)) {
            throw new TemplateNotFoundException($template_name, $template_path);
        }
        return $template_path;
    }

    public function get_template_path(string $template_name){
        return $this->ThemeController->get_template_dir_path($template_name);
    }

    public function get_template_model(string $template_name): TemplateModel {
        return static::$array_of_template_models[$template_name];
    }
}
