<?php 

namespace Tests\Unit\Implementations\DistExporter;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Implementations\DistExporter\DistExportService;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\DirectoryService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class DistExportServiceTest extends TestCase {

    /** @test */
    public function itShouldCreatePageAssetPathCorrectly() {

        // Given we have a DistExportService instance and a PageModel
        $configuration = $this->createMock(ConfigurationInterface::class);
        $directoryService = $this->createMock(DirectoryService::class);
        $fileSystem = $this->createMock(Filesystem::class);
        $themeService = $this->createMock(ThemeServiceInterface::class);
        $exportService = new DistExportService(
            configuration: $configuration,
            directoryService: $directoryService,
            filesystem: $fileSystem,
            themeService: $themeService
        );
        $pageModel = new PageModel(
            id: '12345',
            name: 'test-page',
            title: 'Test Page',
            urlPath: '/blogs/test-page.html',
            theme: new ThemeModel('test-theme'),
            template: new TemplateModel('test-template'), 
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            staticAssetRegistry: new StaticAssetRegistry(),
            blockRegistry: new BlockRegistry()
        );

        // When we create an asset path for a CSS file
        $cssAssetPath = $exportService->createPageAssetPath($pageModel, 'css');

        // Then the asset path should be correct
        $this->assertEquals('/assets/blogs/test-page.css', $cssAssetPath);

        // When we create an asset path for a JS file
        $jsAssetPath = $exportService->createPageAssetPath($pageModel, 'js');

        // Then the asset path should be correct
        $this->assertEquals('/assets/blogs/test-page.js', $jsAssetPath);

    }

    /** @test */
    public function itShouldReplaceFilenameWithPageId() {
        // Given we have a DistExportService instance and a PageModel
        $configuration = $this->createMock(ConfigurationInterface::class);
        $directoryService = $this->createMock(DirectoryService::class);
        $fileSystem = $this->createMock(Filesystem::class);
        $themeService = $this->createMock(ThemeServiceInterface::class);
        $exportService = new DistExportService(
            configuration: $configuration,
            directoryService: $directoryService,
            filesystem: $fileSystem,
            themeService: $themeService
        );

        // When we replace the filename with a page ID
        $hashedUrl = $exportService->replaceFilenameWithPageId(
            '/blogs/articles/test-page.html', '12345'
        );

        // Then the URL should be correctly hashed
        $this->assertEquals('/blogs/articles/12345.html', $hashedUrl);
    }

    /** @test */
    public function itShouldExportPageHtml() {
        // Given we have a DistExportService instance and a PageModel
        $configuration = $this->createMock(ConfigurationInterface::class);
        $directoryService = $this->createMock(DirectoryService::class);
        $fileSystem = $this->createMock(Filesystem::class);
        $themeService = $this->createMock(ThemeServiceInterface::class);
        $exportService = new DistExportService(
            configuration: $configuration,
            directoryService: $directoryService,
            filesystem: $fileSystem,
            themeService: $themeService
        );
        $pageModel = new PageModel(
            id: '12345',
            name: 'test-page',
            title: 'Test Page',
            urlPath: '/blogs/test-page.html',
            theme: new ThemeModel('test-theme'),
            template: new TemplateModel('test-template'), 
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            staticAssetRegistry: new StaticAssetRegistry(),
            blockRegistry: new BlockRegistry()
        );

        // Mock the filesystem to expect a dumpFile call
        $fileSystem->expects($this->once())
                   ->method('dumpFile')
                   ->with($this->stringContains('/blogs/test-page.html'), 'Test HTML Content');

        // When we export the page HTML
        $exportService->exportPageHtml($pageModel, 'Test HTML Content');
    }

    /** @test */
    public function isShouldExportPageCss() {
        // Given we have a DistExportService instance and a PageModel
        $configuration = $this->createMock(ConfigurationInterface::class);
        $directoryService = $this->createMock(DirectoryService::class);
        $fileSystem = $this->createMock(Filesystem::class);
        $themeService = $this->createMock(ThemeServiceInterface::class);
        $exportService = new DistExportService(
            configuration: $configuration,
            directoryService: $directoryService,
            filesystem: $fileSystem,
            themeService: $themeService
        );
        $pageModel = new PageModel(
            id: '12345',
            name: 'test-page',
            title: 'Test Page',
            urlPath: '/blogs/test-page.html',
            theme: new ThemeModel('test-theme'),
            template: new TemplateModel('test-template'), 
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            staticAssetRegistry: new StaticAssetRegistry(),
            blockRegistry: new BlockRegistry()
        );

        // Mock the filesystem to expect a dumpFile call
        $fileSystem->expects($this->once())
                   ->method('dumpFile')
                   ->with($this->stringContains('/assets/blogs/test-page.css'), 'Test CSS Content');

        // When we export the page CSS
        $exportService->exportPageCss($pageModel, 'Test CSS Content');
    }

}