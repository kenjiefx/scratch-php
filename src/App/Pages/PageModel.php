<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetRegistry;
use Kenjiefx\ScratchPHP\App\Blocks\BlockRegistry;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;

/**
 * Represents a page in the static site.
 */
class PageModel {

    public function __construct(

        /**
         * The name of the page. 
         * This is typically the filename without the extension.
         */
        public readonly string $name,

        /**
         * The title of the page.
         */
        public readonly string $title,

        /**
         * The URL path of the page.
         * This is the path where the page will be accessible.
         * This path always starts with a slash (e.g., "/about.html", "/contact.html").
         * This path also includes the ".html" extension.
         */
        public readonly string $urlPath,

        /**
         * The theme associated with the page.
         */
        public readonly ThemeModel $theme,

        /**
         * The template used to render the page.
         * This contains the layout for the page.
         */
        public readonly TemplateModel $template,

        /**
         * The data associated with the page.
         * This can include content, metadata, and other information.
         */
        public readonly PageData $data,

        /**
         * The component registry for the page.
         * This contains all components that can be used in the page.
         */
        public readonly ComponentRegistry $componentRegistry,

        /**
         * The block registry for the page.
         * This contains all blocks that can be used in the page.
         */
        public readonly BlockRegistry $blockRegistry,

        /**
         * The static asset registry for the page.
         * This contains all static assets (images, etc) used in the page.
         */
        public readonly StaticAssetRegistry $staticAssetRegistry
    ) {}

}