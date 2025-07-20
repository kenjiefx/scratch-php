<?php

namespace Tests\Unit\Implementations\VanillaPHP\PageBuilders;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockData;
use Kenjiefx\ScratchPHP\App\Blocks\BlockModel;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentData;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\StaticAssetBundler;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PageJavaScriptBuilderTest extends TestCase {

    /** @test */
    public function itShouldBuildBlockJs() {

        // Given we have PageJavaScriptBuilder object and PageModel
        $container = new Container();
        $blockRegistry = new BlockRegistry();
        $blockRegistry->register(new BlockModel(
            id: 'example1',
            name: 'ExampleBlocks/ExampleBlock1',
            data: new BlockData()
        ));
        $blockRegistry->register(new BlockModel(
            id: 'example2',
            name: 'ExampleBlocks/ExampleBlock2',
            data: new BlockData()
        ));
        $pageModel = new PageModel(
            name: 'index',
            title: 'Home Page',
            urlPath: '/',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: $blockRegistry,
            staticAssetRegistry: new StaticAssetRegistry()
        );
        $container->delegate(new ReflectionContainer());
        $container->add(ThemeServiceInterface::class, new class extends ThemeService {
            public function __construct() {}
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
        $container->add(StaticAssetBundler::class, new class extends StaticAssetBundler {
            public function __construct() {}
            public function bundleJsAssets(PageModel $pageModel): string {
                return "";
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
            name: 'ExampleComponents/ExampleComponent1',
            data: new ComponentData()
        ));
        $componentRegistry->register(new ComponentModel(
            id: 'example1',
            name: 'ExampleComponents/ExampleComponent2',
            data: new ComponentData()
        ));
        $pageModel = new PageModel(
            name: 'index',
            title: 'Home Page',
            urlPath: '/',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: $componentRegistry,
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );
        $container->delegate(new ReflectionContainer());
        $container->add(ThemeServiceInterface::class, new class extends ThemeService {
            public function __construct() {}
            public function getComponentJsPath(ThemeModel $themeModel, ComponentModel $componentModel): string {
                return "{$componentModel->name}.js";
            }
        });
        $container->add(Filesystem::class, new class extends Filesystem {
            public function exists(array|string|\Traversable $files): bool{ return true; }
            public function readFile(array|string|\Traversable $files): string {
                return $files;
            }
        });
        $container->add(StaticAssetBundler::class, new class extends StaticAssetBundler {
            public function __construct() {}
            public function bundleJsAssets(PageModel $pageModel): string {
                return "";
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