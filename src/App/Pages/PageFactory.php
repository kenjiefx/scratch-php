<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Pages\PageJSON;
use Kenjiefx\ScratchPHP\App\Templates\TemplateFactory;

/**
 * Handles the creation of the PageModel, because the PageModel itself requires
 * a lot of things to create. 
 */
class PageFactory
{

    private static int $pageIdIncrementor = 1;

    public static function create(PageJSON $PageJSON):PageModel{
        $PageJSON->unpack();
        $PageModel = new PageModel(
            id: strval(Self::$pageIdIncrementor++).uniqid(),
            name: $PageJSON->filename,
            TemplateModel: TemplateFactory::create($PageJSON->template),
            dir: $PageJSON->reldir,
            title: $PageJSON->title,
            data: new PageData($PageJSON->data)
        );
        return $PageModel;
    }

}
