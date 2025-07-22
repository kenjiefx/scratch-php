<?php

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Events\EventInterface;

/**
 * This event is triggered when the HTML for a component is created.
 * It allows for additional processing or modifications to the component's HTML.
 */
class ComponentHTMLCreatedEvent implements EventInterface {

    public function __construct(
        /**
         * The ComponentModel instance representing the component.
         * @var ComponentModel
         */
        public readonly ComponentModel $component,

        /**
         * The HTML content generated for the component.
         * @var string
         */
        public string $content
    ) {}

}