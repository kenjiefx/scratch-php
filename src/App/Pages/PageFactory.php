<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Utils\UniqueIdGenerator;

class PageFactory {

    public function __construct(
        private UniqueIdGenerator $uniqueIdGenerator
    ) {}

    public function create(
        string $name,
        string $title,
        string $template,
        string $theme,
        string | null $urlPath = null,
        array $data = [],
    ): PageModel {
        // If name starts with a slash, remove it
        $name = ltrim($name, '/');
        $urlPath ??= "/{$name}.html";
        $pageData = new PageData();
        foreach ($data as $key => $value) {
            $pageData[$key] = $value;
        }
        $theme = new ThemeModel($theme);
        $templateModel = new TemplateModel($template);
        $pageId = $this->uniqueIdGenerator->generate();
        return new PageModel(
            id: $pageId,
            name: $name,
            title: $title,
            urlPath: $urlPath,
            theme: $theme,
            template: $templateModel,
            data: $pageData,
            componentRegistry: new ComponentRegistry(),
            blockRegistry: new BlockRegistry(),
            staticAssetRegistry: new StaticAssetRegistry()
        );
    }

}