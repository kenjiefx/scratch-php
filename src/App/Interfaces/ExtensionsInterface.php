<?php

namespace Kenjiefx\ScratchPHP\App\Interfaces;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;

interface ExtensionsInterface
{
    /**
     * Allow extension to receive pre-processed/raw page
     * HTML, recently compiled from the build phase.
     * @param string $rawPageHTML
     */
    // public function mutatePageHTML(string $pageHTML):string;

    // public function mutatePageCSS(string $pageCSS):string;

    // public function mutatePageJS(string $pageJS):string;

    // public function onCreateComponentContent(ComponentModel $componentModel, string $content):string;

    // public function onCreateComponentCSS(ComponentModel $componentModel, string $css): string;

    // public function onCreateComponentJS(ComponentModel $componentModel, string $js): string;
}
