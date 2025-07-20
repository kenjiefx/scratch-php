<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * Event triggered when the JavaScript content for a page has been built.
 * This event carries the PageModel and the generated JavaScript content.
 */
class PageJSBuildCompleteEvent implements EventInterface {
    
    public function __construct(
        /**
         * The PageModel associated with the built JS content.
         */
        public readonly PageModel $pageModel,
        /**
         * The generated JS content for the page.
         */
        public string $content
    ) {}

}