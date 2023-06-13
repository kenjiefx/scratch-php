<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\MissingPageJsonFieldException;

/**
 * Represents each of the JSON file in the PageRegistry::$directory. Each
 * JSON file corresponds to a specific page and contains essential information 
 * such as the page title and additional data required during the build process.
 */
class PageJSON
{
    private string $templateName;
    private string $pageTitle;
    private array $pageData;
    private string $fileName;
    private string $relPath;

    public function __construct(){

    }

    /**
     * Parses and validates Page JSON file.
     */
    public function unpackFromSrc(string $path){

        $data = json_decode(file_get_contents($path),TRUE);

        if (!isset($data['template'])) throw new MissingPageJsonFieldException('template',$path);
        $this->templateName = $data['template'];

        if (!isset($data['title'])) throw new MissingPageJsonFieldException('title',$path);
        $this->pageTitle = $data['title'];

        if (isset($data['data'])) $this->pageData = $data['data'];

        $this->deriveMetadataFromPath($path);

        return $this;
    }

    private function deriveMetadataFromPath (string $path){
        $pathTokens  = explode('/',$path);
        $fileNamePos = count($pathTokens) - 1;
        [$this->fileName,] = explode('.',$pathTokens[$fileNamePos]);
        array_shift($pathTokens);
        array_pop($pathTokens);
        $this->relPath = implode('/',$pathTokens);
    }

    public function getTemplateName(){
        return $this->templateName;
    }

    public function getPageTitle(){
        return $this->pageTitle;
    }

    public function getPageData(){
        return $this->pageData ?? [];
    }

    public function getFileName(){
        return $this->fileName;
    }

    public function getRelPath(){
        return $this->relPath;
    }


}
