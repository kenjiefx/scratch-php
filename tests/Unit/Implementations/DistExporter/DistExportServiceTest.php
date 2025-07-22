<?php 

namespace Tests\Unit\Implementations\DistExporter;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Implementations\DistExporter\DistExportService;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use PHPUnit\Framework\TestCase;

class DistExportServiceTest extends TestCase {

    /** @test */
    public function itShouldCreatePageAssetPathCorrectly() {

        // Given we have a DistExportService instance and a PageModel
        $exportService = new DistExportService();
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

}