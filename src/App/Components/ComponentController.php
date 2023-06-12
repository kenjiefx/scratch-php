<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Exceptions\ComponentAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentController
{

    private ComponentModel $componentModel;
    private string $component_name;

    public function __construct(
        private ThemeController $ThemeController
    ){
        AppSettings::load();
    }

    public function set_component_name(string $component_name){
        $this->component_name = $component_name;
    }

    public function get_component_directory(){
        return $this->ThemeController->get_component_dir_path($this->component_name);
    }


    public function create_component(string $component_name){

        $this->set_component_name($component_name);
        $this->ThemeController->mount_theme(AppSettings::get_theme_name_from_config());

        $component_dir_path = $this->get_component_directory();

        if (is_dir($component_dir_path)) {
            throw new ComponentAlreadyExistsException($component_name);
        }

        $component_html_path = $component_dir_path.'/'.$component_name.'.php';
        $component_js_path   = $component_dir_path.'/'.$component_name.'.js';
        $component_css_path  = $component_dir_path.'/'.$component_name.'.css';

        $component_html = $component_css = $component_js = '';

        $component_model = new ComponentModel(
            name:          $component_name,
            template_name: '', 
            html_path:     $component_html_path,
            js_path:       $component_js_path,
            css_path:      $component_css_path
        );

        # Extensions
        foreach (AppSettings::extensions()->get_extensions() as $extension) {
            $component_html = $extension->onCreateComponentContent($component_model,$component_html);
            $component_css  = $extension->onCreateComponentCSS($component_model,$component_css);
            $component_js   = $extension->onCreateComponentJS($component_model,$component_js);
        }

        mkdir($component_dir_path);

        file_put_contents($component_html_path,$component_html);
        file_put_contents($component_js_path,$component_js);
        file_put_contents($component_css_path,$component_css);

    }
}
