<?php

namespace Kenjiefx\ScratchPHP\App\Themes;

class ThemeModel
{
    public function __construct(
        private string $name,
        private string $dirPath
    ){

    }

    public function getDirPath(){
        return $this->dirPath;
    }

    public function getThemeName(){
        return $this->name;
    }
}
