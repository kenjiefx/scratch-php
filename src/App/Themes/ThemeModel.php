<?php

namespace Kenjiefx\ScratchPHP\App\Themes;

/**
 * The ThemeModel provides methods and properties to manage and manipulate theme-related data, 
 * such as retrieving or setting theme options, applying the theme to different components or pages, 
 * and potentially even supporting customization or dynamic theme changes.
 */
class ThemeModel
{
    public function __construct(
        private string $name,
        private string $libPath
    ){
        
    }

    /** Returns the path of the theme in the library */
    public function getDirPath():string {
        return $this->libPath;
    }

    /** Returns the name of the theme */
    public function getName():string {
        return $this->name;
    }
}
