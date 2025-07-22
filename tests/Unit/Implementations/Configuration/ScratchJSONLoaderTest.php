<?php 

namespace Tests\Unit\Implementations\Configuration;

use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONLoader;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

class ScratchJSONLoaderTest extends TestCase {

    /** @test */
    public function itShouldThrowAnErrorWhenConfigJsonDoesNotExist() {

        // Given we have a configuration object
        $fileSystem = $this->createMock(Filesystem::class);
        $fileSystem->method('exists')->willReturn(false);
        $configuration = new ScratchJSONLoader($fileSystem);

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
        $configuration = new ScratchJSONLoader($fileSystem);

        // It should throw an error when the scratch.json file is not parsable
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Error parsing scratch.json configuration file.");
        $configuration->loadConfigFromJson();
    }

}