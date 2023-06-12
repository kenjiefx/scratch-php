<?php

namespace Kenjiefx\ScratchPHP\App\Exceptions;

class SnippetNotFoundException extends \Exception
{
    public function __construct(string $snippet_name, string $snippet_path){
        $error  = 'Snippet Not Found! Attempt to use snippet named "'.$snippet_name.'" ';
        $error .= 'when it is not found in this theme in this path: '.$snippet_path;
        parent::__construct($error);
    }
}
