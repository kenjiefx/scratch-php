<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * This event is triggered when HTML for a component is collected.
 */
class ComponentHTMLCollectedEvent implements EventInterface {

    public function __construct(
        /**
         * The page model associated with the component.
         * @var PageModel
         */
        public readonly PageModel $page,

        /**
         * The component model for which HTML is collected.
         * @var ComponentModel
         */
        public readonly ComponentModel $component,

        /**
         * The collected HTML content for the component.
         * @var string
         */
        public string $content
    ) {}

}