<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\PageCollectorInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageData;
use Kenjiefx\ScratchPHP\App\Pages\PageIterator;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

class EditorPageCollectorService implements PageCollectorInterface {

    public function __construct(
        private ConfigurationInterface $configuration
    ) {}

    public function collectAll(): PageIterator {
        $themeName = $this->configuration->getThemeName();
        $pageModel = new PageModel(
            name: 'editor',
            title: 'ScratchPHP Theme Editor',
            urlPath: '/editor',
            theme: new ThemeModel($themeName),
            template: new TemplateModel('default'),
            data: new PageData(),
            componentRegistry: new ComponentRegistry(),
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );
        return new PageIterator([$pageModel]);
    }

    public function collectByName(string $name): PageIterator {
        return $this->collectAll();
    }

    public function doesExist(string $name): bool {
        return true;
    }


}