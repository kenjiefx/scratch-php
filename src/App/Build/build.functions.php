<?php 
use Kenjiefx\ScratchPHP\App\Build\BuildHelpers;
use Kenjiefx\ScratchPHP\App\Components\ComponentController;
use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Exceptions\ComponentNotFoundException;
use Kenjiefx\ScratchPHP\App\Exceptions\SnippetNotFoundException;
use Kenjiefx\ScratchPHP\App\Exceptions\TemplateNotFoundException;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;


function page_title(){
    $PageController = BuildHelpers::PageController();
    echo $PageController->getPageTitle();
}

/**
 * Embeds CSS and Javascript for each of the pages
 */
function template_assets(){
    $PageController = BuildHelpers::PageController();
    $pageRelPath    = $PageController->getPageRelPath();
    $pageAssetsName = $PageController->getAssetsName();

    $parentDirName  = 'assets';
    $totalParentDir = count(explode('/',$pageRelPath))-1;
    $i = 0;
    while ($i<$totalParentDir) {
        $parentDirName = '../'.$parentDirName;
        $i++;
    }

    $assetsRelDir = $parentDirName.$pageRelPath.'/'.$pageAssetsName;

    echo '<script type="text/javascript" src="'.$assetsRelDir.'.js?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$assetsRelDir.'.css?v='.time().'">';
}


function get_assets_name(){
    return (BuildHelpers::PageController())->getAssetsName();
}


/**
 * Renders the content of the template used by the page.
 */
function template_content(){
    $PageController = BuildHelpers::PageController();
    $templateFilePath = $PageController->getTemplate()->getFilePath();
    if (!file_exists($templateFilePath)) {
        throw new TemplateNotFoundException(
            $PageController->getTemplate()->getTemplateName(),
            $PageController->getTemplate()->getFilePath()
        );
    }
    include $templateFilePath; 
}

/**
 * Renders a component into templates or other components. This 
 * function also registers the component into the Template Model.
 * @param string $name - The name of the component to render
 */
function component(string $name, array $data = []){

    $ComponentController = new ComponentController(ComponentFactory::create($name));

    # Validates whether the component exists in your theme
    if (!file_exists($ComponentController->getHtmlPath())) {
        throw new ComponentNotFoundException($name,$ComponentController->getHtmlPath());
    }
    
    $PageController = BuildHelpers::PageController();
    $PageController->getTemplate()->registerComponent($ComponentController->getComponent());
    $component = $data;
    include $ComponentController->getHtmlPath();
}

/**
 * Renders a snippet into templates, components, or other snippets. 
 * @TODO This function registers the snippet into the Template Model
 * @param string $snippet_name = The file name of the snippet
 */
function snippet(string $snippetName, array $data = []){
    $ThemeController = ContainerFactory::create()->get(ThemeController::class);
    $snippetFilePath = $ThemeController->getSnippetFilePath($snippetName);
    if (!file_exists($snippetFilePath)) {
        throw new SnippetNotFoundException($snippetName, $snippetFilePath);
    }
    $snippet = $data;
    include $snippetFilePath;
}

/**
 * Allows access to the page data declared in page.json
 * @param string field
 */
function page_data(string $field){
    $PageController = BuildHelpers::PageController();
    $data = $PageController->getPageData();
    return (isset($data[$field])) ? $data[$field] : null;
}