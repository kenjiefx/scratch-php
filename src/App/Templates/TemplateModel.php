<?php

namespace Kenjiefx\ScratchPHP\App\Templates;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;

class TemplateModel
{

    /**
     * The names of the components used/declared in a specific template. 
     * You can use the name to retrieve the specific Component Model 
     * from the Component Registry
     */
    private array $component_usage = [];

    /**
     * All template information are registered into the Template Object. 
     * However, since this application will render different pages which could 
     * be using the same template, duplicate information might get registered,
     * thus, we set the isFrozen value to TRUE after the processing of the 
     * very first page that is using this template.
     */
    private bool $is_frozen = false;

    public function __construct(
        private string $id,
        private string $name,
        private string $template_path
    ){
        
    }

    public function get_template_path(){
        return $this->template_path;
    }

    public function has_used_component(string $component_name){
        return isset($this->component_usage[$component_name]);
    }

    public function add_component (ComponentModel $ComponentModel){
        if ($this->is_frozen) return;
        $component_name = $ComponentModel->get_component_name();
        if (!isset($this->component_usage[$component_name])) {
            $this->component_usage[$component_name] = [
                'model'  => $ComponentModel,
                'usage' => 0
            ];
        }
        $this->component_usage[$component_name]['usage']++;
    }

    public function list_used_components(){
        $components = [];
        foreach ($this->component_usage as $name => $data) {
            array_push($components, $data['model']);
        }
        return $components;
    }

    public function has_been_frozen(){
        return $this->is_frozen;
    }

    public function get_id(){
        return $this->id;
    }

    public function freeze() {
        $this->is_frozen = true;
    }
}
