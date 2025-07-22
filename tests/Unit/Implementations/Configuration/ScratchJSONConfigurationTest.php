<?php 

namespace Tests\Unit\Implementations\Configuration;

use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONConfiguration;
use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ScratchJSONConfigurationTest extends TestCase {


    /** @test */
    public function itShouldLoadRequiredFieldsSuccessfully() {

        // Given we have a configuration object with valid JSON
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist"}');

        $configuration = new ScratchJSONConfiguration(
            new ScratchJSONLoader($fileSystem)
        );

        // When we load the configuration
        $configuration->loadRequiredFields(
            json_decode($fileSystem->readFile('/mock/path.json'), true)
        );

        // Then it should load the required fields successfully
        $this->assertEquals('default', $configuration->getThemeName());
        $this->assertEquals('dist', $configuration->getExportDir());
    }

    /** @test */
    public function itShouldSetOptionalFields() {
        // Given we have a configuration object with valid JSON
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist", "baseUrl": "/app"}');

        $configuration = new ScratchJSONConfiguration(
            new ScratchJSONLoader($fileSystem)
        );

        // When we load the optional fields
        $configuration->loadOptionalFields(
            json_decode($fileSystem->readFile('/mock/path.json'), true)
        );

        // Then it should set the baseUrl correctly
        $this->assertEquals('/app', $configuration->getBaseUrl());
    }

    /** @test */
    public function itShouldSetOptionalFieldsWhenNotProvided() {
        // Given we have a configuration object with valid JSON
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist"}');

        $configuration = new ScratchJSONConfiguration(
            new ScratchJSONLoader($fileSystem)
        );

        // When we load the optional fields
        $configuration->loadOptionalFields(
            json_decode($fileSystem->readFile('/mock/path.json'), true)
        );

        // Then it should set the baseUrl to default value
        $this->assertEquals('/', $configuration->getBaseUrl());
    }

    /** @test */
    public function itShouldSetBuildConfigFields() {
        // Given we have a configuration object with valid JSON
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist", "build": {"useHashedFilenames": true, "pageToBuild": "index.json"}}');

        $configuration = new ScratchJSONConfiguration(
            new ScratchJSONLoader($fileSystem)
        );

        // When we load the build configurations
        $configuration->loadBuildConfigs(
            json_decode($fileSystem->readFile('/mock/path.json'), true)
        );

        // Then it should set the build configurations correctly
        $this->assertTrue($configuration->useHashedFilenames());
        $this->assertEquals('index.json', $configuration->pageToBuild());
    }

}