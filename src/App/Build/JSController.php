<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class JSController
{
    private string|null $global_js = null;

    public function __construct(
        private ThemeController $ThemeController
    ){

    }

    public function collect_global_js(){
        if (null===$this->global_js) {
            $this->global_js  = '';
            $assets_directory = $this->ThemeController->get_assets_dir_path();
            foreach(scandir($assets_directory) as $file_name) {
                if ($file_name==='.'||$file_name==='..') continue;
                if (explode('.',$file_name)[1]!=='js') continue; 
                $assets_path = $assets_directory.'/'.$file_name;
                $this->global_js .= PHP_EOL.file_get_contents($assets_path);
            }
        }
        return $this->global_js;
    }

    public function collect_component_js(TemplateModel $TemplateModel){
        $collected_js = '';
        foreach ($TemplateModel->list_used_components() as $ComponentModel) {
            $component_name = $ComponentModel->get_component_name();
            $js_file_path = $this->ThemeController->get_component_dir_path($component_name).'/'.$component_name.'.js';
            
            if (file_exists($js_file_path)) {
                $collected_js .= PHP_EOL.file_get_contents($js_file_path);
            }
        }
        return $collected_js;
    }
}
