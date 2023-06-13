<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentsIterator;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class TemplateController
{

    public function __construct(
        private TemplateModel $TemplateModel
    ){
        
    }

    public function getFilePath(){
        $ThemeController = new ThemeController();
        return $ThemeController->getTemplateFilePath($this->TemplateModel->getName());
    }

    public function getUtilizedComponents():ComponentsIterator{
        $ComponentModels = [];
        foreach ($this->TemplateModel->getComponents() as $Registry) {
            array_push($ComponentModels,$Registry['model']);
        }
        $ComponentsIterator = new ComponentsIterator($ComponentModels);
        return $ComponentsIterator;
    }

    public function getTemplateName(){
        return $this->TemplateModel->getName();
    }

    public function registerComponent(ComponentModel $ComponentModel) {
        $this->TemplateModel->registerComponent($ComponentModel);
    }

    public function create_template(string $template_name){

        // $this->ThemeController->mount_theme(AppSettings::get_theme_name_from_config());

        // $template_path = $this->ThemeController->get_template_dir_path($template_name);
        // if (file_exists($template_path)) {
        //     throw new TemplateAlreadyExistsException($template_name);
        // }
        // $template_html = '';
        // foreach (AppSettings::extensions()->get_extensions() as $extension) {
        //     if (method_exists($extension,'onCreateTemplate')) {
        //         $template_html .= $extension->onCreateTemplate($template_html);
        //     }
        // }
        // file_put_contents($template_path,$template_html);
    }

}
