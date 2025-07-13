<?php

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Events\{BuildCompletedEvent, PageBuildStartedEvent, PageBuildCompletedEvent, CSSBuildCompletedEvent, EventDispatcher, HTMLBuildCompletedEvent, JSBuildCompletedEvent};
use Kenjiefx\ScratchPHP\App\Exports\{ExporterInterface, ExportFactory};
use Kenjiefx\ScratchPHP\App\Pages\{PageModel, PageRegistry};
use Kenjiefx\ScratchPHP\App\Themes\{ThemeFactory, ThemeModel};
use Kenjiefx\ScratchPHP\App\Statics\StaticAssetsRegistry;

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
        public readonly JSBuildService $jSBuildService,
        public readonly StaticAssetsRegistry $staticAssetsRegistry,
    ) {}

    public function build(PageRegistry $pageRegistry): void
    {
        $themeModel = $this->themeFactory->create(
            $this->configuration->getThemeName()
        );

        $this->staticAssetsRegistry->clear();

        foreach ($pageRegistry as $pageModel) {
            $this->dispatchPageBuildStartEvent($pageModel);
            $this->componentRegistry->clear();
            $this->buildHtml($themeModel, $pageModel);
            $this->buildAsset($themeModel, $pageModel, 'css');
            $this->buildAsset($themeModel, $pageModel, 'js');
            $this->dispatchPageBuildCompletedEvent($pageModel);
        }

        $this->exportStaticAssets();

        $this->dispatchCompletedEvent();
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

    private function dispatchCompletedEvent(): void
    {
        $event = new BuildCompletedEvent(
            $this->configuration->getExportDir()
        );
        $this->eventDispatcher->dispatchEvent($event);
    }

    private function dispatchPageBuildStartEvent(
        PageModel $pageModel
    ){
        $event = new PageBuildStartedEvent($pageModel);
        $this->eventDispatcher->dispatchEvent($event);
    }

    private function dispatchPageBuildCompletedEvent(
        PageModel $pageModel
    ){
        $event = new PageBuildCompletedEvent($pageModel);
        $this->eventDispatcher->dispatchEvent($event);
    }

    private function exportStaticAssets() {
        foreach ($this->staticAssetsRegistry->getAll() as $staticAssetModel) {
            $exportModel = $this->exportFactory->createAsStaticAsset($staticAssetModel);
            $this->exporter->export($exportModel);
        }
    }
}
