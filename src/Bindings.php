<?php

namespace Kenjiefx\ScratchPHP;

use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\BuildServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionCollectorInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use League\Container\Container as ContainerProvider;

/**
 * Binding various interfaces to their implementations in the container.
 */
class Bindings {

    public function __construct(
        private ContainerProvider $container
    ) {}

    public function ConfigurationInterface(ConfigurationInterface $configuration): void {
        $this->container->add(ConfigurationInterface::class, $configuration);
    }

    public function BuildServiceInterface(BuildServiceInterface $buildService): void {
        $this->container->add(BuildServiceInterface::class, $buildService);
    }

    public function ExportServiceInterface(ExportServiceInterface $exportService): void {
        $this->container->add(ExportServiceInterface::class, $exportService);
    }

    public function ExtensionCollectorInterface(ExtensionCollectorInterface $extensionCollector): void {
        $this->container->add(ExtensionCollectorInterface::class, $extensionCollector);
    }

    public function PageCollectorInterface(PageCollectorInterface $pageCollector) : void {
        $this->container->add(PageCollectorInterface::class, $pageCollector);
    }

    public function ThemeServiceInterface(ThemeServiceInterface $themeService): void {
        $this->container->add(ThemeServiceInterface::class, $themeService);
    }

}