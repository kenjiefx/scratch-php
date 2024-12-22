<?php

namespace Kenjiefx\ScratchPHP\App\Themes;

class ThemePaths {

    public function __construct(
        public readonly string $components,
        public readonly string $templates,
        public readonly string $snippets,
        public readonly string $assets,
        public readonly string $index
    ){

    }



}