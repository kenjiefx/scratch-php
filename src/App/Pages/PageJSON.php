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
    /**
     * Name of the page template
     * @var string
     */
    public readonly string $template;

    /**
     * Title given to the page
     * @var string
     */
    public readonly string $title;

    /**
     * Custom configurations
     * @var array
     */
    public readonly array $data;

    /**
     * Name of the Page JSON file 
     * @var string
     */
    public readonly string $filename;

    /**
     * Relative director where Page JSON 
     * file exists
     * @var string
     */
    public readonly string $reldir;

    public function __construct(
        private string $path
    ){

    }

    /**
     * Extracts, validates, and transforms data 
     * stored in Page JSON file
     */
    public function unpack(){

        $configuration = 
            json_decode(
                json: file_get_contents(filename: $this->path),
                associative: TRUE
            );

        if (!isset($configuration['template'])) {
            throw new MissingPageJsonFieldException(
                missing_field: 'template',
                template_path: $this->path
            );
        }

        if (!isset($configuration['title'])) {
            throw new MissingPageJsonFieldException(
                missing_field: 'title',
                template_path: $this->path
            );
        }

        $this->template = $configuration['template'];
        $this->title = $configuration['title'];
        $this->data = $configuration['data'] ?? [];

        $this->filename 
            = pathinfo(
                path: $this->path, 
                flags: PATHINFO_FILENAME
            );
        $reldir = str_replace(
            search: ROOT . PageRegistry::PAGES_DIR,
            replace: '', 
            subject: dirname($this->path)
        );
        if (isset($reldir[0]) && $reldir[0] === '/') {
            $reldir = substr($reldir, 1);
        }

        $this->reldir = $reldir;

    }

}
