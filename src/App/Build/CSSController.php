<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class CSSController
{

    private string|null $global_css = null;

    public function __construct(
        private ThemeController $ThemeController
    ){

    }

    public function collect_global_css(){

        if (null===$this->global_css) {
            $this->global_css = '';

            $assets_directory = $this->ThemeController->get_assets_dir_path();
            foreach(scandir($assets_directory) as $file_name) {

                if ($file_name==='.'||$file_name==='..') continue;
                if (explode('.',$file_name)[1]!=='css') continue; 

                $assets_path       = $assets_directory.'/'.$file_name;
                $this->global_css .= file_get_contents($assets_path);
            }
        }
        return $this->global_css;
    }

    public function collect_component_css(TemplateModel $TemplateModel){
        $collected_css = '';
        foreach ($TemplateModel->list_used_components() as $ComponentModel) {
            $component_name = $ComponentModel->get_component_name();
            $css_file_path = $this->ThemeController->get_component_dir_path($component_name).'/'.$component_name.'.css';
            
            if (file_exists($css_file_path)) {
                $collected_css .= file_get_contents($css_file_path);
            }
        }
        return $collected_css;
    }


}
