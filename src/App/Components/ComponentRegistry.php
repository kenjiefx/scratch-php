<?php

namespace Kenjiefx\ScratchPHP\App\Components;

/** 
 * Registers and manages the components user within the template. 
 * This object holds an array of Component Controllers.
 */
class ComponentRegistry {

    /** An array of Component Controllers */
    private array $registry = [];

    public function __construct(){
        
    }

    public function register(ComponentModel $ComponentModel){
        $ComponentCtrl = new ComponentController($ComponentModel);
        array_push($this->registry, $ComponentCtrl);
    }

    public function get(): ComponentsIterator {
        $ComponentIterator = new ComponentsIterator($this->registry);
        return $ComponentIterator;
    }



}