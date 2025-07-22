<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON;

use Symfony\Component\Filesystem\Filesystem;

class ScratchJSONLoader {

    public function __construct(
        private Filesystem $fileSystem,
    ) {}

    public function loadConfigFromJson() {
        $scratchJsonPath = $this->getJsonPath();
        if (!$this->fileSystem->exists($scratchJsonPath)) {
            $message = "Missing scratch.json configuration file.";
            throw new \Exception($message);
        }
        $jsonContent = $this->fileSystem->readFile($scratchJsonPath);
        $configs = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $message = "Error parsing scratch.json configuration file.";
            throw new \Exception($message);
        }
        return $configs;
    }

    private function getJsonPath() {
        return ROOT . "/scratch.json";
    }

}