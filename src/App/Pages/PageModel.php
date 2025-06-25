<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

/**
 * Represents a singe page JSON in the <rootdir>/pages directory.
 * 
 * This class encapsulates the properties and data associated with a page,
 * including its ID, name, template, directory, title, and additional data.
 */
class PageModel {

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly TemplateModel $templateModel,
        // Holds the actual path to the page JSON file
        public readonly File $file,
        public readonly string $title,
        public readonly PageData $data
    ) {}

    

}