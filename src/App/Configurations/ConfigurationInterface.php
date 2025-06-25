<?php 

namespace Kenjiefx\ScratchPHP\App\Configurations;

interface ConfigurationInterface {

    public function getRootDir(): string;

    public function exportDir(): string;

    public function getThemeName(): string;

    public function useHashedFilenames(): bool;

    public function pageToBuild(): string | null;

    public function getExtensions(): array;

}