<?php 

namespace Kenjiefx\ScratchPHP\App\Implementations\ScratchJSON;

use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;

class ScratchJSONConfiguration implements ConfigurationInterface {

    private static string $rootDir;
    protected static string $exportDir;
    protected static string $themeName;
    protected static string $baseUrl;
    protected static bool $toUseHashedFilenames = false;
    protected static string | null $pageToBuild = null;

    public function __construct(
        private ScratchJSONLoader $loader,
    ) {}

    public function loadConfig(): void {
        static::$rootDir = ROOT;
        // Loading the scratch.json configuration file
        $configs = $this->loader->loadConfigFromJson();
        // Loading required fields
        $this->loadRequiredFields($configs);
        // Loading optional configurations
        $this->loadOptionalFields($configs);
        // Loading optional build configurations
        $this->loadBuildConfigs($configs);
    }

    public function loadRequiredFields(array $configs): void {
        if (!isset($configs['theme'])) {
            $message = "Missing theme field in scratch.json";
            throw new \Exception($message);
        }
        if (!isset($configs['exportDir'])) {
            $message = "Missing exportDir field in scratch.json";
            throw new \Exception($message);
        }
        static::$themeName = $configs['theme'];
        static::$exportDir = $configs['exportDir'];
    }

    public function loadOptionalFields(array $configs) {
        static::$baseUrl = $configs['baseUrl'] ?? '/';
    }

    public function loadBuildConfigs(array $configs): void {
        $buildConfigs = $configs['build'] ?? [];
        static::$toUseHashedFilenames = $buildConfigs['useHashedFilenames'] ?? false;
        static::$pageToBuild = $buildConfigs['pageToBuild'] ?? null;
    }

    public function getRootDir(): string
    {
        if (!isset(static::$rootDir)) {
            throw new \Exception("Root directory is not set.");
        }
        return static::$rootDir;
    }

    public function getExportDir(): string
    {
        if (!isset(static::$exportDir)) {
            throw new \Exception("Export directory is not set.");
        }
        return static::$exportDir;
    }

    public function getThemeName(): string
    {
        if (!isset(static::$themeName)) {
            throw new \Exception("Theme name is not set.");
        }
        return static::$themeName;
    }

    public function useHashedFilenames(): bool
    {
        return static::$toUseHashedFilenames;
    }

    public function pageToBuild(): string | null
    {
        return static::$pageToBuild;
    }

    public function getBaseUrl(): string
    {
        if (!isset(static::$baseUrl)) {
            throw new \Exception("Base URL is not set.");
        }
        return static::$baseUrl;
    }

}