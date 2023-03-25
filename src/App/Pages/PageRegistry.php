<?php

namespace Kenjiefx\ScratchPHP\App\Pages;

class PageRegistry
{

    private static string $directory = '\pages';
    private static array $pageModels;

    public function __construct(
        private PageFactory $pageFactory
    ){
        if (!isset(static::$pageModels)) {
            static::$pageModels = [];
        }
    }

    public function discover(){
        $pathToPages = ROOT.static::$directory;
        $this->crawl($pathToPages);
    }

    private function crawl(string $pageDirPath){
        $pages = scandir($pageDirPath);
        foreach ($pages as $page) {
            if ($page==='.'||$page==='..') continue;
            $pagePath = $pageDirPath.'/'.$page;
            if (is_dir($pagePath)) {
                $this->crawl($pagePath);
                return;
            }
            $id = count(static::$pageModels)+1;
            array_push(
                static::$pageModels,
                $this->pageFactory::create($id.''.uniqid(),$pagePath)
            );
        }
    }

    public function clearBin(){
        @array_map('unlink', array_filter((array) glob(__dir__."/bin/*") ) );
        file_put_contents(__dir__.'/bin/README.md','');
    }

    public function getPages(){
        return static::$pageModels;
    }
}
