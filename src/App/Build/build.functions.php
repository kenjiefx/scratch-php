<?php 
use Kenjiefx\ScratchPHP\App\Components\ComponentRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;


function page_title(){
    $pageModel = $GLOBALS['__page_model'];
    echo $pageModel->getTitle();
}

/**
 * Embeds CSS and Javascript for each of the pages
 */
function template_assets(){
    $pageModel = $GLOBALS['__page_model'];
    $pageDirPath = ($pageModel->getDirPath()==='') ? '' : '/'.$pageModel->getDirPath();

    $assetsFileName = (AppSettings::build()->useRandomAssetsFileNames()===true) ? $pageModel->getId() : $pageModel->getName();
    $assetsRelativeDir = '../assets'.$pageDirPath.'/'.$assetsFileName;
    echo '<script type="text/javascript" src="'.$assetsRelativeDir.'.js?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$assetsRelativeDir.'.css?v='.time().'">';
}

/**
 * Renders the content of the template used by the page.
 */
function template_content(){
    $templateRegistry = ContainerFactory::create()->get(TemplateRegistry::class);
    $templateName = $GLOBALS['__page_model']->getTemplateName();
    $templatePath = $templateRegistry->getTemplatePath(templateName: $templateName);
    include $templatePath; 
}

/**
 * Renders a component into templates or other components. This 
 * function also registers the component into the Template Model.
 * @param string $componentName - The name of the component to render
 */
function component(
    string $componentName
){
    $templateName = $GLOBALS['__page_model']->getTemplateName();
    $templateRegistry = ContainerFactory::create()->get(TemplateRegistry::class);
    $templateModel = $templateRegistry->getTemplateModel($templateName);
    $componentRegistry = ContainerFactory::create()->get(ComponentRegistry::class);
    $componentModel = $componentRegistry->register(
        templateName: $templateName,
        componentName :$componentName
    );
    $componentHtmlPath = $componentModel->getComponentHtmlPath();
    if (!file_exists($componentHtmlPath)) {
        $error = 'Component Not Found. Attempt to use component named "'.$componentName.'" ';
        $error .= 'when it is not found in the theme in this path: '.$componentHtmlPath;
        throw new \Exception($error);
    }
    if ($templateModel->hasUsedComponent($componentName)&&!$templateModel->hasbeenFrozen()) {
        if (!AppSettings::build()->allowReusableComponentsWithinTemplate()) {
            $error = 'Duplicate Components Within Template! Attempt to use component "'.$componentName.'" ';
            $error .= 'within the template "'.$templateName.'" while the setting "build.reuseComponentsWithinTemplate" ';
            $error .= 'is enabled and set to false.';
            throw new \Exception($error);
        }
    }
    $templateModel->addComponent($componentName);
    include $componentHtmlPath;
}

/**
 * Renders a snippet into templates, components, or other snippets. 
 * @TODO This function registers the snippet into the Template Model
 * @param string $snippetName = The file name of the snippet
 */
function snippet(
    string $snippetName
){
    $themeController = ContainerFactory::create()->get(ThemeController::class);
    $snippetPath = $themeController->getSnippetPath($snippetName);
    if (!file_exists($snippetPath)) {
        $error = 'Snippet Not Found! Attempt to use snippet named "'.$snippetName.'" ';
        $error .= 'when it is not found in this theme in this path: '.$snippetPath;
        throw new \Exception($error);
    }
    include $snippetPath;
}