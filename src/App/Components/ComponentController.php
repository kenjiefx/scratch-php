<?php

namespace Kenjiefx\ScratchPHP\App\Components;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Exceptions\ComponentAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ComponentController
{
    private ThemeController $ThemeController;

    public function __construct(
        private ComponentModel $ComponentModel
    ){
        $this->ThemeController = ContainerFactory::create()->get(ThemeController::class);
    }

    public function setComponent(ComponentModel $ComponentModel){
        $this->ComponentModel = $ComponentModel;
    }

    public function getComponent():ComponentModel {
        return $this->ComponentModel;
    }

    public function getDirPath(){
        return $this->ThemeController->getComponentsDirPath($this->ComponentModel->getName());
    }

    public function getHtmlPath(){
        return $this->getDirPath().'/'.$this->ComponentModel->getName().'.php';
    }




    public function create_component(string $component_name, array $options){

        // $this->set_component_name($component_name);
        // $this->ThemeController->mount_theme(AppSettings::get_theme_name_from_config());

        // $component_dir_path = $this->get_component_directory();

        // if (is_dir($component_dir_path)) {
        //     throw new ComponentAlreadyExistsException($component_name);
        // }

        // $component_html_path = $component_dir_path.'/'.$component_name.'.php';
        // $component_js_path   = $component_dir_path.'/'.$component_name.'.js';
        // $component_css_path  = $component_dir_path.'/'.$component_name.'.css';

        // $component_html = $component_css = $component_js = '';

        // $component_model = new ComponentModel(
        //     name:          $component_name,
        //     template_name: '', 
        //     html_path:     $component_html_path,
        //     js_path:       $component_js_path,
        //     css_path:      $component_css_path
        // );

        // # Extensions
        // if ($options['apply_extensions']) {
        //     foreach (AppSettings::extensions()->get_extensions() as $extension) {
        //         $component_html = $extension->onCreateComponentContent($component_model,$component_html);
        //         $component_css  = $extension->onCreateComponentCSS($component_model,$component_css);
        //         $component_js   = $extension->onCreateComponentJS($component_model,$component_js);
        //     }
        // }
        
        // mkdir($component_dir_path);

        // file_put_contents($component_html_path,$component_html);
        // file_put_contents($component_js_path,$component_js);
        // file_put_contents($component_css_path,$component_css);

    }
}
