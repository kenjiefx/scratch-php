<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Pages\PageContent;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;


interface BuildServiceInterface {

    public function buildPage(PageModel $pageModel): PageContent;

}