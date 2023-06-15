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
        $PageModel = new PageModel(
            pageId: strval(Self::$pageIdIncrementor++).uniqid(),
            pageName: $PageJSON->getFileName(),
            TemplateModel: TemplateFactory::create($PageJSON->getTemplateName()),
            dirPath: $PageJSON->getRelPath(),
            pageTitle: $PageJSON->getPageTitle(),
            pageData: $PageJSON->getPageData()
        );
        return $PageModel;
    }

}
