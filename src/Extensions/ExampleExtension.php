<?php

namespace Kenjiefx\ScratchPHP\Extensions;
use Kenjiefx\ScratchPHP\App\Build\BuildEventDTO;
use Kenjiefx\ScratchPHP\App\Build\CollectComponentAssetEventDTO;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Components\ComponentEventDTO;
use Kenjiefx\ScratchPHP\App\Configuration\CommandsRegistry;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Events\OnBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnBuildJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCollectComponentJsEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateComponentHtmlEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateTemplateEvent;
use Kenjiefx\ScratchPHP\App\Events\OnCreateThemeEvent;
use Kenjiefx\ScratchPHP\App\Events\OnDeployEvent;
use Kenjiefx\ScratchPHP\App\Events\OnSettingsRegistryEvent;
use Kenjiefx\ScratchPHP\App\Extensions\RegisterCommand;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateController;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

#[RegisterCommand(CommandExtension::class)]
class ExampleExtension implements ExtensionsInterface
{

    private array $extensionSettings = [];

    #[ListensTo(OnBuildHtmlEvent::class)]
    public function processHtml(BuildEventDTO $BuildEventDTO){
        $BuildEventDTO->content 
            = $BuildEventDTO->content 
            . ' Hello this is added from ExampleExtension ';
    }

    #[ListensTo(OnSettingsRegistryEvent::class)]
    public function registerSettings(array $settings){
        $this->extensionSettings = $settings;
    }

    #[ListensTo(OnBuildJsEvent::class)]
    public function processJavascript(BuildEventDTO $BuildEventDTO){
        $BuildEventDTO->content 
            = $BuildEventDTO->content 
            . ' console.log("hello world from Example Extension!") ';
    }

    #[ListensTo(OnCreateComponentHtmlEvent::class)]
    public function appendNewComponentHtml(ComponentEventDTO $ComponentEventDTO){
        $htmlpath = $ComponentEventDTO->ComponentController->paths()->html();
        $ComponentEventDTO->content = 'Hello world, from: '.$htmlpath;
    }

    #[ListensTo(OnCreateThemeEvent::class)]
    public function doSomethingAfterThemeIsCreated(ThemeController $ThemeController){
        $testSnippet = $ThemeController->getdir().'/snippets/test.snippet.php';
        file_put_contents($testSnippet,'Hello, this is created using extension!');
    }

    #[ListensTo(OnBuildCompleteEvent::class)]
    public function doSomethingAfterBuildCommand(string $exportDirPath){
        if (!isset($this->extensionSettings['createTestHtml'])) return;
        if (!$this->extensionSettings['createTestHtml']) return;
        file_put_contents($exportDirPath.'/test.html','Hello World!');
    }

    #[ListensTo(OnDeployEvent::class)]
    public function deployApp(){
        echo 'App is deployed!';
    }

    #[ListensTo(OnCreateTemplateEvent::class)]
    public function doSomethingWhenTemplateIsCreated(TemplateController $TemplateController){
        $name = $TemplateController->TemplateModel->name;
        $jspath = $TemplateController->getdir() . $name . '.js';
        //file_put_contents($jspath, '');
    }

    #[ListensTo(OnCollectComponentJsEvent::class)]
    public function componentJSCollectionListener(CollectComponentAssetEventDTO $CollectComponentAssetEventDTO){
        $componentName = $CollectComponentAssetEventDTO->ComponentController->ComponentModel->name;
        $CollectComponentAssetEventDTO->content .= ' console.log("This is updated upon componentJsCollect for component '.$componentName.'"); ';
    }
}
