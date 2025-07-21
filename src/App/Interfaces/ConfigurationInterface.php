<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

interface ConfigurationInterface {

    public function loadConfig(): void;

    public function getRootDir(): string;

    public function getExportDir(): string;

    public function getThemeName(): string;

    public function useHashedFilenames(): bool;

    public function pageToBuild(): string | null;

    public function getBaseUrl(): string;

}