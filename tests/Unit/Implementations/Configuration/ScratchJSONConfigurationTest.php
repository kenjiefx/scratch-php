<?php 

namespace Tests\Unit\Implementations\Configuration;

use Kenjiefx\ScratchPHP\App\Implementations\Configuration\ScratchJSONConfiguration;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ScratchJSONConfigurationTest extends TestCase {

    /** @test */
    public function itShouldThrowAnErrorWhenConfigJsonDoesNotExist() {

        // Given we have a configuration object
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(false);
        $configuration = new ScratchJSONConfiguration(
            $fileSystem
        );

        // It should throw an error when the scratch.json file does not exist
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Missing scratch.json configuration file.");
        $configuration->loadConfigFromJson();

    }

    /** @test */
    public function itShouldThrowAnErrorWhenConfigJsonIsNotParsable() {

        // Given we have a configuration object
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist}'); // Invalid JSON

        $configuration = new ScratchJSONConfiguration(
            $fileSystem
        );

        // It should throw an error when the scratch.json file is not parsable
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error parsing scratch.json configuration file.");
        $configuration->loadConfigFromJson();
    }

    /** @test */
    public function itShouldLoadRequiredFieldsSuccessfully() {

        // Given we have a configuration object with valid JSON
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(true);
        $fileSystem->method('readFile')->willReturn('{"theme": "default", "exportDir": "dist"}');

        $configuration = new ScratchJSONConfiguration(
            $fileSystem
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
            $fileSystem
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
            $fileSystem
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
            $fileSystem
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