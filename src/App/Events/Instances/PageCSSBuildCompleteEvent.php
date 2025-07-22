<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * Event triggered when the CSS content for a page has been built.
 * This event carries the PageModel and the generated CSS content.
 */
class PageCSSBuildCompleteEvent implements EventInterface {

    public function __construct(
        /**
         * The PageModel associated with the built CSS content.
         */
        public readonly PageModel $pageModel,
        /**
         * The generated CSS content for the page.
         */
        public string $content
    ) {}

}