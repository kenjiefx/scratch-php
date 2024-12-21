<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

class PageModel
{

    public function __construct(
        public readonly string $id,
        public readonly string $name,
        public readonly TemplateModel $TemplateModel,
        public readonly string $dir,
        public readonly string $title,
        public readonly PageData $data
    ){

    }

}
