<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
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

    #[ListensTo(OnCreateComponentHtmlEvent::class)]
    public function appendNewComponentHtml(ComponentController $ComponentController){
        $html = $ComponentController->getComponent()->getHtml();
        $ComponentController->getComponent()->setHtml($html.'Hello world');
        return null;
    }
}
