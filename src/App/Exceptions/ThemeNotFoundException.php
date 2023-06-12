<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class ThemeNotFoundException
{
    public function __construct(string $theme_name, string $path_to_theme_directory){
        $error = 'Theme Not Found! Your scratch.config.json declares to use the theme ';
        $error .= 'named "'.$theme_name.'", but the theme does not exist in this path: '.$path_to_theme_directory;
    }
}
