<?php

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Events\{CSSBuildCompletedEvent, EventDispatcher, HTMLBuildCompletedEvent, JSBuildCompletedEvent};
use Kenjiefx\ScratchPHP\App\Exports\{ExporterInterface, ExportFactory};
use Kenjiefx\ScratchPHP\App\Pages\{PageModel, PageRegistry};
use Kenjiefx\ScratchPHP\App\Themes\{ThemeFactory, ThemeModel};

class BuildService
{
    public function __construct(
        public readonly ConfigurationInterface $configuration,
        public readonly ExporterInterface $exporter,
        public readonly ThemeFactory $themeFactory,
        public readonly ComponentRegistry $componentRegistry,
        public readonly EventDispatcher $eventDispatcher,
        public readonly HTMLBuildService $htmlBuildService,
        public readonly CSSBuildService $cssBuildService,
        public readonly ExportFactory $exportFactory,
        public readonly JSBuildService $jSBuildService
    ) {}

    public function build(PageRegistry $pageRegistry): void
    {
        $themeModel = $this->themeFactory->create(
            $this->configuration->getThemeName()
        );

        foreach ($pageRegistry as $pageModel) {
            $this->componentRegistry->clear();
            $this->buildHtml($themeModel, $pageModel);
            $this->buildAsset($themeModel, $pageModel, 'css');
            $this->buildAsset($themeModel, $pageModel, 'js');
        }
    }

    public function buildHtml(ThemeModel $themeModel, PageModel $pageModel): void
    {
        $this->handleBuildStep(
            fn() => $this->htmlBuildService->build($themeModel, $pageModel),
            fn($content) => new HTMLBuildCompletedEvent($pageModel, $content),
            fn($content) => $this->exportFactory->createFromPage($pageModel, $content)
        );
    }

    private function buildAsset(ThemeModel $themeModel, PageModel $pageModel, string $type): void
    {
        $service = [
            'css' => $this->cssBuildService,
            'js' => $this->jSBuildService,
        ][$type] ?? throw new \InvalidArgumentException("Unsupported asset type: $type");

        $eventClass = [
            'css' => CSSBuildCompletedEvent::class,
            'js' => JSBuildCompletedEvent::class,
        ][$type];

        $this->handleBuildStep(
            fn() => $service->build($themeModel),
            fn($content) => new $eventClass($pageModel, $content),
            fn($content) => $this->exportFactory->createAsAsset($pageModel, $type, $content)
        );
    }

    private function handleBuildStep(
        callable $buildFn,
        callable $eventFactory,
        callable $exportFactory
    ): void {
        $content = $buildFn();
        $event = $eventFactory($content);
        $this->eventDispatcher->dispatchEvent($event);
        $updatedContent = $event->getContent();
        $exportModel = $exportFactory($updatedContent);
        $this->exporter->export($exportModel);
    }
}
