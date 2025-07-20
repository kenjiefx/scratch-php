<?php

namespace Tests\Unit\Implementations\VanillaPHP\PageBuilders;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class PageJavaScriptBuilderTest extends TestCase {

    /** @test */
    public function itShouldBuildPageJs() {

        // Given we have PageJavaScriptBuilder object and PageModel
        $container = new Container();
        $blockRegistry = new BlockRegistry();
        $blockRegistry->register(new BlockModel(
            name: 'ExampleBlocks/ExampleBlock1'
        ));
        $blockRegistry->register(new BlockModel(
            name: 'ExampleBlocks/ExampleBlock2'
        ));
        $pageModel = new PageModel(
            name: 'index',
            title: 'Home Page',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: $blockRegistry,
            staticAssetRegistry: new StaticAssetRegistry()
        );
        $container->delegate(new ReflectionContainer());
        $container->add(ThemeServiceInterface::class, new class implements ThemeServiceInterface {
            public function getAssetsDir(ThemeModel $themeModel): string {return '';}
            public function getTemplatesDir(ThemeModel $themeModel): string {return '';}
            public function getIndexPath(ThemeModel $themeModel): string {return '';}
            public function getComponentJsPath(ThemeModel $themeModel, ComponentModel $componentModel): string {return '';}
            public function getBlockJsPath(ThemeModel $themeModel, BlockModel $blockModel): string {
                return "{$blockModel->name}.js";
            }
        });
        $container->add(Filesystem::class, new class extends Filesystem {
            public function exists(array|string|\Traversable $files): bool{ return true; }
            public function readFile(array|string|\Traversable $files): string {
                return $files;
            }
        });
        $pageJsBuilder = $container->get(PageJavaScriptBuilder::class);

        // When we build js of all blocks
        $result = $pageJsBuilder->buildBlockJS($pageModel);

        // Then we expect that the result matched 
        $this->assertEquals(
            "ExampleBlocks/ExampleBlock1.jsExampleBlocks/ExampleBlock2.js",
            $result
        );

    }

    /** @test */
    public function itShouldBuildComponentJs() {

        // Given we have PageJavaScriptBuilder object and PageModel
        $container = new Container();
        $componentRegistry = new ComponentRegistry();
        $componentRegistry->register(new ComponentModel(
            id: 'example1',
            name: 'ExampleComponents/ExampleComponent1'
        ));
        $componentRegistry->register(new ComponentModel(
            id: 'example1',
            name: 'ExampleComponents/ExampleComponent2'
        ));
        $pageModel = new PageModel(
            name: 'index',
            title: 'Home Page',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: $componentRegistry,
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );
        $container->delegate(new ReflectionContainer());
        $container->add(ThemeServiceInterface::class, new class implements ThemeServiceInterface {
            public function getAssetsDir(ThemeModel $themeModel): string {return '';}
            public function getTemplatesDir(ThemeModel $themeModel): string {return '';}
            public function getIndexPath(ThemeModel $themeModel): string {return '';}
            public function getComponentJsPath(ThemeModel $themeModel, ComponentModel $componentModel): string {
                return "{$componentModel->name}.js";
            }
            public function getBlockJsPath(ThemeModel $themeModel, BlockModel $blockModel): string {return '';}
        });
        $container->add(Filesystem::class, new class extends Filesystem {
            public function exists(array|string|\Traversable $files): bool{ return true; }
            public function readFile(array|string|\Traversable $files): string {
                return $files;
            }
        });
        $pageJsBuilder = $container->get(PageJavaScriptBuilder::class);

        // When we build js of all blocks
        $result = $pageJsBuilder->buildComponentJS($pageModel);

        // Then we expect that the result matched 
        $this->assertEquals(
            "ExampleComponents/ExampleComponent1.jsExampleComponents/ExampleComponent2.js",
            $result
        );

    }

}