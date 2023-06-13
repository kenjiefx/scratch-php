<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class ExampleExtension implements ExtensionsInterface
{

    #[\ListensTo(OnBuildHtmlEvent::class)]
    public function processHtml(){
        
    }
}
