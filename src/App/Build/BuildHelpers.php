<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Pages\PageController;

class BuildHelpers
{
    private static PageController $PageController;

    public static function PageController(PageController|null $PageController=null):PageController{
        if ($PageController===null) return static::$PageController;
        static::$PageController = $PageController;
        return $PageController;
    }

}
