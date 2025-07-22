<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;

/**
 * This event is triggered when CSS for a component is collected.
 */
class ComponentCSSCollectedEvent implements EventInterface {

    public function __construct(
        /**
         * The page model associated with the component.
         * @var PageModel
         */
        public readonly PageModel $page,

        /**
         * The component model for which CSS is collected.
         * @var ComponentModel
         */
        public readonly ComponentModel $component,

        /**
         * The collected CSS content for the component.
         * @var string
         */
        public string $content
    ) {}

}