<?php 

namespace Kenjiefx\ScratchPHP\App\Interfaces;

use Kenjiefx\ScratchPHP\App\Pages\PageModel;


interface BuildServiceInterface {

    public function buildPageHtml(PageModel $pageModel): string;

    public function buildPageJavascript(PageModel $pageModel): string;

    public function buildPageCSS(PageModel $pageModel): string;

}