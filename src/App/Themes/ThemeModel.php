<?php 

namespace Kenjiefx\ScratchPHP\App\Themes;

/**
 * ThemeModel represents a theme in the application.
 */
class ThemeModel {

    public function __construct(

        /**
         * The name of the theme.
         * @var string
         */
        public readonly string $name
        
    ) {}

}