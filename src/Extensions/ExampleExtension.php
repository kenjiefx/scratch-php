<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExampleExtension implements ExtensionsInterface
{

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function processHtml(string $html){
        return $html;
    }

    #[ListensTo(OnBuildJsEvent::class)]
    public function processJavascript(string $js){
        return $js.'console.log("hello world!")';
    }
}
