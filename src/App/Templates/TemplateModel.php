<?php 

namespace Kenjiefx\ScratchPHP\App\Templates;

/**
 * TemplateModel class represents a template with a name.
 */
class TemplateModel {

    public function __construct(
        /**
         * The name of the template.
         * @var string
         */
        public readonly string $name
    ) {}

}