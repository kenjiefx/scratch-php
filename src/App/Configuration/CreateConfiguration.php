<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;

/**
 * Class BuildConfiguration
 * Settings and configuration when calling the strawberry build command
 */
class CreateConfiguration
{
    public function __construct(
        private array $configuration
        )
    {
        
    }

    public function useComponentTypeScript(){
        return $this->configuration['useComponentTypeScript'] ?? false;
    }
}
