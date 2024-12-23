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
        public readonly string $name,
        public readonly string $dirpath
    ){
        
    }

    
}
