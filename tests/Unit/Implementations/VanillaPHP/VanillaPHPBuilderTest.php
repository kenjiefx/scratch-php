<?php

namespace Tests\Unit\Implementations\VanillaPHP;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONConfiguration;
use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferService;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageCSSBuilder;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\PageBuilders\PageJavaScriptBuilder;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\VanillaPHPBuilder;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use League\Container\Container;
use League\Container\ReflectionContainer;
use PHPUnit\Framework\TestCase;

class VanillaPHPBuilderTest extends TestCase {

    /** @test */
    public function itShouldBuildJsContent() {
        // Given we have VanillaPHPBuilder and PageModel instance
        $container = new Container();
        $container->delegate(new ReflectionContainer());
        $container->add(PageJavaScriptBuilder::class, new class extends PageJavaScriptBuilder {
            public function __construct() {}
            public function build($pageModel): string {
                return "console.log('Hello, World!');";
            }
        });
        $container->add(ThemeServiceInterface::class, new class extends ThemeService {
            public function __construct() {}
        });
        $builder = $container->get(VanillaPHPBuilder::class);
        $pageModel = new PageModel(
            id: '1',
            name: 'index',
            title: 'Home Page',
            urlPath: '/',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );

        // When we call buildPageJavascript
        $jsContent = $builder->buildPageJavascript($pageModel);

        // Then it should return the expected JavaScript content
        $this->assertEquals("console.log('Hello, World!');", $jsContent);

    }

    /** @test */
    public function itShouldBuildCSSContent() {
        // Given we have VanillaPHPBuilder and PageModel instance
        $container = new Container();
        $container->delegate(new ReflectionContainer());
        $container->add(PageCSSBuilder::class, new class extends PageCSSBuilder {
            public function __construct() {}
            public function build($pageModel): string {
                return "body { background-color: #fff; }";
            }
        });
        $container->add(ThemeServiceInterface::class, new class extends ThemeService {
            public function __construct() {}
        });
        $builder = $container->get(VanillaPHPBuilder::class);
        $pageModel = new PageModel(
            id: '2',
            name: 'index',
            title: 'Home Page',
            urlPath: '/',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );

        // When we call buildPageCSS
        $cssContent = $builder->buildPageCSS($pageModel);

        // Then it should return the expected CSS content
        $this->assertEquals("body { background-color: #fff; }", $cssContent);
    }

    /** @test */
    public function itShouldBuildHTMLContent() {
        // Given we have VanillaPHPBuilder and PageModel instance
        $container = new Container();
        $container->delegate(new ReflectionContainer());
        $container->add(OutputBufferService::class, new class extends OutputBufferService {
            public function __construct() {}
            public function capture($apiFilePath, $themeIndexPath, $outputBufferContent): string {
                return "<html><head></head><body>Test HTML Content</body></html>";
            }
        });
        $container->add(ConfigurationInterface::class, new class extends ScratchJSONConfiguration {
            public function __construct() {}
        });
        $container->add(ThemeServiceInterface::class, new class extends ThemeService {
            public function __construct() {}
            public function getIndexPath(ThemeModel $themeModel): string {
                return "";
            }
        });
        $container->add(ThemeServiceInterface::class, new ThemeService(
            $container->get(ConfigurationInterface::class)
        ));
        $builder = $container->get(VanillaPHPBuilder::class);
        $pageModel = new PageModel(
            id: '3',
            name: 'index',
            title: 'Home Page',
            urlPath: '/',
            theme: new ThemeModel('example-theme'),
            template: new TemplateModel('example-template'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );

        // When we call buildPageHtml
        $htmlContent = $builder->buildPageHtml($pageModel);

        // Then it should return the expected HTML content
        $this->assertEquals("<html><head></head><body>Test HTML Content</body></html>", $htmlContent);
    }

}