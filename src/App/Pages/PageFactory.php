<?php

namespace Kenjiefx\ScratchPHP\App\Pages;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;

class PageFactory
{
    public static function create(
        string $pageId,
        string $pagePath
    ){
        [$fileName,$dirPath] = PageFactory::extractMetadata(path:$pagePath);
        $pageJson = PageFactory::parseJson($pagePath);
        $pageModel = new PageModel(
            id: $pageId,
            binId: self::createBinId($dirPath,$fileName), 
            name: $fileName,
            templateName: $pageJson['templateName'],
            dirPath: $dirPath,
            title: $pageJson['title'] ?? $fileName
        );
        return $pageModel;
    }

    public static function createBinId(
        string $dirPath,
        string $fileName
    ){
        return str_replace('/','.',$dirPath).'.'.$fileName;
    }

    public static function extractMetadata(
        string $path
    ){
        $tokens = explode('/',$path);
        $length = count($tokens) - 1;
        $fileName = explode('.',$tokens[$length])[0];
        array_shift($tokens);
        array_pop($tokens);
        return [
            $fileName, implode('/',$tokens)
        ];
    }

    public static function parseJson(
        string $path
    ){
        $data = json_decode(file_get_contents($path),TRUE);
        if (!isset($data['template'])) {
            $error = 'Page Configuration Error. This exception was thrown because the page.json';
            $error .= 'file did not contain template field. Please see: '.$path;
            throw new ConfigurationException($error);
        }
        return [
            'templateName' => $data['template'],
            'title' => $data['title'] ?? null
        ];
    }


}
