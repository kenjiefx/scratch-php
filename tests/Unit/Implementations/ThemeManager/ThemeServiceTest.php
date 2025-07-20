<?php 

namespace Tests\Unit\Implementations\ThemeManager;

use Kenjiefx\ScratchPHP\App\Components\ComponentData;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Implementations\ThemeManager\ThemeService;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use PHPUnit\Framework\TestCase;

class ThemeServiceTest extends TestCase {

    /** @test */
    public function itShouldReturnThemeDirFromTheThemeModelName() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme/test-theme');
        $actualDir = $themeService->getThemeDir($themeModel);

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnThemesDirFromConfiguration() {
        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme');
        $actualDir = $themeService->getThemesDir();

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnAssetsDirFromThemeModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme/test-theme/assets');
        $actualDir = $themeService->getAssetsDir($themeModel);

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnTemplatesDirFromThemeModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme/test-theme/templates');
        $actualDir = $themeService->getTemplatesDir($themeModel);

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnTemplatePathFromThemeAndTemplateModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );
        $templateModel = new TemplateModel(
            name: 'header'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedPath = $themeService->normalizePath('/var/www/html/theme/test-theme/templates/header.php');
        $actualPath = $themeService->getTemplatePath($themeModel, $templateModel);

        $this->assertEquals($expectedPath, $actualPath);
    }

    /** @test */
    public function itShouldReturnIndexPathFromThemeModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedPath = $themeService->normalizePath('/var/www/html/theme/test-theme/index.php');
        $actualPath = $themeService->getIndexPath($themeModel);

        $this->assertEquals($expectedPath, $actualPath);
    }

    /** @test */
    public function itShouldReturnComponentsDirFromThemeModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme/test-theme/components');
        $actualDir = $themeService->getComponentsDir($themeModel);

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnComponentDirFromThemeAndComponentModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );
        $componentModel = new ComponentModel(
            id: '123',
            name: 'ExampleComponents/ComponentName',
            data: new ComponentData()
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedDir = $themeService->normalizePath('/var/www/html/theme/test-theme/components/ExampleComponents/ComponentName');
        $actualDir = $themeService->getComponentDir($themeModel, $componentModel);

        $this->assertEquals($expectedDir, $actualDir);
    }

    /** @test */
    public function itShouldReturnComponentJsPathFromThemeAndComponentModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );
        $componentModel = new ComponentModel(
            id: '123',
            name: 'ExampleComponents/ComponentName',
            data: new ComponentData()
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedPath = $themeService->normalizePath('/var/www/html/theme/test-theme/components/ExampleComponents/ComponentName/ComponentName.js');
        $actualPath = $themeService->getComponentJsPath($themeModel, $componentModel);

        $this->assertEquals($expectedPath, $actualPath);
    }

    /** @test */
    public function itShouldReturnComponentCssPathFromThemeAndComponentModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );
        $componentModel = new ComponentModel(
            id: '123',
            name: 'ExampleComponents/ComponentName',
            data: new ComponentData()
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedPath = $themeService->normalizePath('/var/www/html/theme/test-theme/components/ExampleComponents/ComponentName/ComponentName.css');
        $actualPath = $themeService->getComponentCssPath($themeModel, $componentModel);

        $this->assertEquals($expectedPath, $actualPath);
    }

    /** @test */
    public function itShouldReturnComponentHtmlPathFromThemeAndComponentModel() {
        $themeModel = new ThemeModel(
            name: 'test-theme'
        );
        $componentModel = new ComponentModel(
            id: '123',
            name: 'ExampleComponents/ComponentName',
            data: new ComponentData()
        );

        $configuration = $this->createMock(ConfigurationInterface::class);
        $configuration->method('getRootDir')->willReturn('/var/www/html');

        $themeService = new ThemeService($configuration);

        $expectedPath = $themeService->normalizePath('/var/www/html/theme/test-theme/components/ExampleComponents/ComponentName/ComponentName.php');
        $actualPath = $themeService->getComponentHtmlPath($themeModel, $componentModel);

        $this->assertEquals($expectedPath, $actualPath);
    }


}