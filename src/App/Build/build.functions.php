<?php 
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Exceptions\ComponentNotFoundException;
use Kenjiefx\ScratchPHP\App\Exceptions\SnippetNotFoundException;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;


function page_title(){
    $page_model = $GLOBALS['__page_model'];
    echo $page_model->get_title();
}

/**
 * Embeds CSS and Javascript for each of the pages
 */
function template_assets(){
    $page_model         = $GLOBALS['__page_model'];
    $page_relative_path = ($page_model->get_directory_path()==='') ? '' : '/'.$page_model->get_directory_path();

    $assets_file_name    = (AppSettings::build()->useRandomAssetsFileNames()===true) ? $page_model->get_id() : $page_model->get_name();
    $assets_relative_dir = '../assets'.$page_relative_path.'/'.$assets_file_name;

    echo '<script type="text/javascript" src="'.$assets_relative_dir.'.js?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$assets_relative_dir.'.css?v='.time().'">';
}


/**
 * Renders the content of the template used by the page.
 */
function template_content(){
    $TemplateRegistry = ContainerFactory::create()->get(TemplateRegistry::class);
    $template_name    = $GLOBALS['__page_model']->get_template_name();
    $template_path    = $TemplateRegistry->get_template_path($template_name);
    include $template_path; 
}

/**
 * Renders a component into templates or other components. This 
 * function also registers the component into the Template Model.
 * @param string $component_name - The name of the component to render
 */
function component(string $component_name){

    # Retrieves the name of the template as registered by the PageModel
    $template_name = $GLOBALS['__page_model']->get_template_name();

    # Instantiating the TemplateModel based on the name of the template
    $TemplateRegistry = ContainerFactory::create()->get(TemplateRegistry::class);
    $TemplateModel    = $TemplateRegistry->get_template_model($template_name);

    # Registers the component to the template model
    $ComponentRegistry   = ContainerFactory::create()->get(ComponentRegistry::class);
    $ComponentModel      = $ComponentRegistry->register($template_name,$component_name);
    $component_html_path = $ComponentModel->get_component_html_path();

    # Validates whether the component exists in your theme
    if (!file_exists($component_html_path)) {
        throw new ComponentNotFoundException($component_name,$component_html_path);
    }
    
    $TemplateModel->add_component($ComponentModel);
    include $component_html_path;
}

/**
 * Renders a snippet into templates, components, or other snippets. 
 * @TODO This function registers the snippet into the Template Model
 * @param string $snippet_name = The file name of the snippet
 */
function snippet(string $snippet_name, array $data = []){
    $ThemeController = ContainerFactory::create()->get(ThemeController::class);
    $snippet_path = $ThemeController->get_snippet_dir_path($snippet_name);
    if (!file_exists($snippet_path)) {
        throw new SnippetNotFoundException($snippet_name, $snippet_path);
    }
    $snippet = $data;
    include $snippet_path;
}

/**
 * Allows access to the page data declared in page.json
 * @param string field
 */
function page_data(string $field){
    $page_model = $GLOBALS['__page_model'];
    $data = $page_model->get_data();
    return (isset($data[$field])) ? $data[$field] : null;
}