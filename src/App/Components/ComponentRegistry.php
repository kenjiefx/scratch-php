<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentRegistry
{

    private static array $array_of_component_models;

    public function __construct(
        private ThemeController $ThemeController
    ){
        if (!isset(static::$array_of_component_models)) {
            static::$array_of_component_models = [];
        }
    }

    public function register(string $template_name, string $component_name):ComponentModel{

        # Creates a new ID for this component, will be used as reference in the static:$array_of_component_models
        $component_id = $this->make_id($template_name, $component_name);

        if (!isset(static::$array_of_component_models[$component_id])) {

            # Usable paths within the application
            $component_dir_path  = $this->ThemeController->get_component_dir_path($component_name);
            $component_html_path = $component_dir_path.'/'.$component_name.'.php';
            $component_js_path   = $component_dir_path.'/'.$component_name.'.js';
            $component_css_path  = $component_dir_path.'/'.$component_name.'.css';

            $component_model = new ComponentModel(
                name:          $component_name,
                template_name: $template_name, 
                html_path:     $component_html_path,
                js_path:       $component_js_path,
                css_path:      $component_css_path
            );

            static::$array_of_component_models[$component_id] = $component_model;
        } 

        return static::$array_of_component_models[$component_id];
    }

    public function make_id(string $template_name, string $component_name){
        return $template_name.':'.$component_name;
    }

    public function get_registered_components(){
        return static::$array_of_component_models;
    }
}
