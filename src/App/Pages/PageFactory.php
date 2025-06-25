<?php 

namespace Kenjiefx\ScratchPHP\App\Pages;

use Kenjiefx\ScratchPHP\App\Files\File;
use Kenjiefx\ScratchPHP\App\Templates\TemplateModel;

/**
 * Creates an instance of PageModel from a JSON file.
 */
class PageFactory {

    private static int $pageIdIncrementor = 1;

    public function __construct(

    ) {}


    public function create(
        string $pageJsonPath
    ){
        // Check if the file exists
        if (!file_exists($pageJsonPath)) {
            throw new \Exception("Page JSON file does not exist: $pageJsonPath");
        }
        // Check if the file is readable
        if (!is_readable($pageJsonPath)) {
            throw new \Exception("Page JSON file is not readable: $pageJsonPath");
        }
        $configuration = 
            json_decode(file_get_contents($pageJsonPath), TRUE);
        // validate the JSON structure
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception(
                "Invalid JSON in page file: $pageJsonPath. Error: " . json_last_error_msg()
            );
        }
        if (!isset($configuration['template'])) {
            throw new \Exception(
                "Missing 'template' field in page JSON: $pageJsonPath"
            );
        }
        if (!isset($configuration['title'])) {
            throw new \Exception(
                "Missing 'title' field in page JSON: $pageJsonPath"
            );
        }
        if (!isset($configuration['data'])) {
            $configuration['data'] = [];
        }
        return new PageModel(
            id: strval(self::$pageIdIncrementor++).uniqid(),
            name: basename($pageJsonPath, '.json'),
            templateModel: new TemplateModel($configuration['template']),
            file: new File(
                $pageJsonPath
            ),
            title: $configuration['title'],
            data: new PageData($configuration['data'])
        );
    }

    

}