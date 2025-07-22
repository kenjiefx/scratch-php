<?php

namespace Kenjiefx\ScratchPHP\App\CLI;

use Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON\ScratchJSONConfiguration;

/**
 * Overrides the ScratchJSONConfiguration for CLI usage.
 * This allows configuration settings to be set via CLI options.
 */
class CLIInputConfiguration extends ScratchJSONConfiguration {

    public function setBaseUrl(string | null $baseUrl) {
        if ($baseUrl !== null) {
            static::$baseUrl = $baseUrl;
        }
    }

    public function setPageToBuild(string | null $pageToBuild) {
        if ($pageToBuild !== null) {
            static::$pageToBuild = $pageToBuild;
        }
    }

    public function setExportDir(string | null $exportDir) {
        if ($exportDir === null) {
            static::$exportDir = $exportDir;
        }
    }

    public function setThemeName(string | null $themeName) {
        if ($themeName !== null) {
            static::$themeName = $themeName;
        }
    }

}