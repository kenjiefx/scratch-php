<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;

class EditorService {

    public static ComponentModel $componentModel;

    public static function setComponentModel(ComponentModel $componentModel): void {
        self::$componentModel = $componentModel;
    }

    public static function getComponentModel(): ComponentModel {
        if (self::$componentModel === null) {
            throw new \Exception("Component model is not set. Please set the component model before getting it.");
        }
        return self::$componentModel;
    }

}