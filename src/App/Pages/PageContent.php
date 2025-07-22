<?php 

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageContent {

    public function __construct(
        public readonly string $html,
        public readonly string $javascript,
        public readonly string $css
    ) {}

}