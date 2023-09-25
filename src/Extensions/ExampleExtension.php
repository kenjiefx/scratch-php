<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateThemeEvent;
use Kenjiefx\ScratchPHP\App\Events\OnSettingsRegistryEvent;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

class ExampleExtension implements ExtensionsInterface
{

    private array $extensionSettings = [];

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function processHtml(string $html){
        return $html;
    }

    #[ListensTo(OnSettingsRegistryEvent::class)]
    public function registerSettings(array $settings){
        $this->extensionSettings = $settings;
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

    #[ListensTo(OnCreateThemeEvent::class)]
    public function doSomethingAfterThemeIsCreated(ThemeController $ThemeController){
        $testSnippet = $ThemeController->getThemeDirPath().'/snippets/test.snippet.php';
        file_put_contents($testSnippet,'Hello, this is created using extension!');
    }

    #[ListensTo(OnBuildCompleteEvent::class)]
    public function doSomethingAfterBuildCommand(string $exportDirPath){
        if (!isset($this->extensionSettings['createTestHtml'])) return;
        if (!$this->extensionSettings['createTestHtml']) return;
        file_put_contents($exportDirPath.'/test.html','Hello World!');
    }
}
