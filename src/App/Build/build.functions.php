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


function page_title() {
    $PageController = BuildHelpers::PageController();
    echo $PageController->PageModel->title;
}

/**
 * Embeds CSS and Javascript for each of the pages
 */
function template_assets(){
    $PageController = BuildHelpers::PageController();
    $pageRelPath    = $PageController->relpath();
    $pageAssetsName = $PageController->assetref();
    
    $assetsRelDir = '/assets'.$pageRelPath.'/'.$pageAssetsName;

    echo '<script type="text/javascript" src="'.$assetsRelDir.'.js?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$assetsRelDir.'.css?v='.time().'">';
}


function get_assets_name(){
    return (BuildHelpers::PageController())->assetref();
}


/**
 * Renders the content of the template used by the page.
 */
function template_content(){
    $PageController = BuildHelpers::PageController();
    $templateFilePath = $PageController->template()->getpath();
    if (!file_exists($templateFilePath)) {
        throw new TemplateNotFoundException(
            $PageController->template()->TemplateModel->name,
            $PageController->template()->getpath()
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

    $ComponentController = new ComponentController(
        ComponentFactory::create($name)
    );

    # Validates whether the component exists in your theme
    if (!file_exists($ComponentController->paths()->html())) {
        throw new ComponentNotFoundException(
            $name,
            $ComponentController->paths()->html()
        );
    }
    
    $PageController = BuildHelpers::PageController();
    $PageController->template()->ComponentRegistry->register(
        $ComponentController->ComponentModel
    );
    $component = $data;
    include $ComponentController->paths()->html();
}

/**
 * Renders a snippet into templates, components, or other snippets. 
 * @TODO This function registers the snippet into the Template Model
 * @param string $snippet_name = The file name of the snippet
 */
function snippet(string $snippetName, array $data = []){
    $ThemeController = ContainerFactory::create()->get(ThemeController::class);
    $snippetFilePath = $ThemeController->path()->snippets . $snippetName . '.php';
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
    return $PageController->PageModel->data->get($field);
}