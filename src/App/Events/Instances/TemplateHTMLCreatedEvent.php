<?php 

namespace Kenjiefx\ScratchPHP\App\Events\Instances;

use Kenjiefx\ScratchPHP\App\Events\EventInterface;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

/**
 * This event is triggered when a new Template is created.
 * It allows for additional processing or modifications 
 * to the template HTML.
 */
class TemplateHTMLCreatedEvent implements EventInterface {

    public function __construct(
        /**
         * The template associated with the event.
         * @var TemplateModel
         */
        public readonly TemplateModel $template,
        /**
         * The HTML content generated for the template.
         * @var string
         */
        public string $content
    ) {}

}