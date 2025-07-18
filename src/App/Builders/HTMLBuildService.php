<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Statics\StaticAssetsRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateServiceInterface;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;

class HTMLBuildService {

    public function __construct(
        public readonly ThemeService $themeService,
        public readonly TemplateServiceInterface $templateService,
        public readonly ComponentRegistry $componentRegistry,
        public readonly BlockRegistry $blockRegistry,
        public readonly StaticAssetsRegistry $staticAssetsRegistry
    ) {}

    /**
     * Builds the HTML for a specific page using the provided theme and template models.
     * 
     * @param string|null $pagePath The path to the specific page to build, or null to build all pages.
     */
    public function build(
        ThemeModel $themeModel,
        PageModel $pageModel
    ) {
        $templateModel = $pageModel->templateModel;
        $componentRegistry = $this->componentRegistry;
        $blockRegistry = $this->blockRegistry;
        $staticAssetsRegistry = $this->staticAssetsRegistry;
        ob_start();
        $templatePath = $this->templateService->getTemplatePath(
            $themeModel,
            $templateModel
        );
        BuildMessageChannel::addListener(
            BuildMessage::TEMPLATE_RENDER,
            function () use ($templatePath) {
                include $templatePath->path;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_THEME,
            function () use ($themeModel) {
                return $themeModel;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_TEMPLATE,
            function () use ($templateModel) {
                return $templateModel;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_PAGE,
            function () use ($pageModel) {
                return $pageModel;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_COMPONENT_REGISTRY,
            function () use ($componentRegistry) {
                return $componentRegistry;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_BLOCK_REGISTRY,
            function () use ($blockRegistry) {
                return $blockRegistry;
            }
        );
        BuildMessageChannel::addListener(
            BuildMessage::GET_STATIC_ASSETS_REGISTRY,
            function () use ($staticAssetsRegistry) {
                return $staticAssetsRegistry;
            }
        );
        include_once __DIR__ . '/theme.apis.php';
        include $this->themeService->getThemeIndexPath($themeModel)->path;
        $content = ob_get_contents();
        ob_end_clean();
        BuildMessageChannel::removeListeners();
        return $content;
    }

}