<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;

/**
 * Class BuildConfiguration
 * Settings and configuration when calling the strawberry build command
 */
class BuildConfiguration
{
    public function __construct(
        private array $configuration
        )
    {
        
    }

    public function useReadableComponentNames()
    {
        return $this->configuration['useReadableComponentName'] ?? false;
    }

    public function allowReusableComponentsWithinTemplate(){
        return $this->configuration['reuseComponentsWithinTemplate'] ?? true;
    }

    public function exportPageWithoutHTMLExtension(){
        return $this->configuration['exportPageWithoutHTMLExtension'] ?? false;
    }

    public function useRandomAssetsFileNames(){
        return $this->configuration['useRandomAssetsFileNames'] ?? false;
    }
}
