<?php 

use Kenjiefx\ScratchPHP\App\Assets\Static\StaticAssetFactory;
use Kenjiefx\ScratchPHP\App\Blocks\BlockFactory;
use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;
use Kenjiefx\ScratchPHP\App\Events\EventBus;
use Kenjiefx\ScratchPHP\App\Events\Instances\ComponentHTMLCollectedEvent;
use Kenjiefx\ScratchPHP\App\Implementations\VanillaPHP\OutputBuffer\OutputBufferContextManager;
use Kenjiefx\ScratchPHP\App\Interfaces\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ExportServiceInterface;
use Kenjiefx\ScratchPHP\App\Interfaces\ThemeServiceInterface;
use Kenjiefx\ScratchPHP\Container;

/**
 * Outputs the title of the current page.
 * This function retrieves the page model from the output buffer context
 * and echoes the title of the page.
 */
function page_title() {
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    echo $pageModel->title;
}

/**
 * Embeds CSS and Javascript for each of the pages.
 * This function retrieves the page's relative path and
 * the asset reference name to construct the assets'
 * relative directory path.
 */
function template_assets(){
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    /** @var ExportServiceInterface */
    $exportService = Container::get(ExportServiceInterface::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    $baseUrl = base_url();
    $jsAssetpath = $exportService->createPageAssetPath($pageModel, 'js');
    // If it starts with a slash, remove it
    if (str_starts_with($jsAssetpath, '/')) {
        $jsAssetpath = substr($jsAssetpath, 1);
    }
    $cssAssetPath = $exportService->createPageAssetPath($pageModel, 'css');
    // If it starts with a slash, remove it
    if (str_starts_with($cssAssetPath, '/')) {
        $cssAssetPath = substr($cssAssetPath, 1);
    }
    echo '<!--start:template_assets-->' . PHP_EOL;
    echo "<script type=\"text/javascript\" src=\"{$baseUrl}{$jsAssetpath}?v=" . time() . "\"></script>" . PHP_EOL;
    echo "<link rel=\"stylesheet\" href=\"{$baseUrl}{$cssAssetPath}?v=" . time() . "\">" . PHP_EOL;
    echo '<!--end:template_assets-->' . PHP_EOL;
}

/**
 * Outputs a static asset link.
 * This function registers the static asset with the static asset registry
 * and outputs the URL to the asset.
 *
 * @param string $fileName The name of the static asset file.
 */
function asset(string $fileName) {
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    /** @var StaticAssetFactory */
    $staticAssetFactory = Container::get(StaticAssetFactory::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    $staticAssetsRegistry = $pageModel->staticAssetRegistry;
    $staticAssetsModel = $staticAssetFactory->create($fileName);
    $staticAssetsRegistry->register($staticAssetsModel);
    $baseUrl = base_url();
    echo "{$baseUrl}assets/{$fileName}?v=" . time();
}


/**
 * Outputs the content of the current template.
 * This function retrieves the template name from the output buffer context
 * and includes the corresponding template file.
 */
function template_content() {
    /** @var ThemeServiceInterface */
    $themeService = Container::get(ThemeServiceInterface::class);
    /** @var OutputBufferContextManager */
    $outputBufferManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferManager->get()->pageModel;
    $templateModel = $pageModel->template;
    $themeModel = $pageModel->theme;
    $templatesPath = $themeService->getTemplatePath(
        $themeModel, $templateModel
    );
    if (file_exists($templatesPath)) {
        include $templatesPath;
    } else {
        throw new \Exception("Template file not found: " . $templatesPath);
    }
}

/**
 * This function is used to render a component.
 * It will include the component's HTML file and 
 * register the component in the component registry.
 * 
 * @param string $name The name of the component including the namespace.
 * @param array $data The data to be passed to the component.
 * @throws \Exception If the component HTML file does not exist.
 */
function component($name, array $data = []) {
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    /** @var ComponentFactory */
    $componentFactory = Container::get(ComponentFactory::class);
    $componentModel = $componentFactory->create($name, $data);
    /** @var ThemeServiceInterface */
    $themeService = Container::get(ThemeServiceInterface::class);
    $componentHtmlPath = $themeService->getComponentHtmlPath(
        $pageModel->theme, $componentModel
    );
    if (!file_exists($componentHtmlPath)) {
        throw new \Exception("Component not found: " . $componentHtmlPath);
    }
    $pageModel->componentRegistry->register($componentModel);
    $component = $data;
    $component['id'] = $componentModel->id;
    ob_start();
    include $componentHtmlPath;
    $content = ob_get_contents();
    ob_end_clean();
    /** @var EventBus */
    $eventBus = Container::get(EventBus::class);
    $event = new ComponentHTMLCollectedEvent(
        $pageModel, $componentModel, $content
    );
    $eventBus->dispatchEvent($event);
    echo $event->content;
}

/**
 * Outputs a block of content.
 * @param mixed $name The name of the block including the namespace.
 * @param array $data The data to be passed to the block.
 * @throws \Exception if the block HTML file does not exist.
 * @return void
 */
function block($name, array $data = []) {
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    $blockRegistry = $pageModel->blockRegistry;
    /** @var BlockFactory */
    $blockFactory = Container::get(BlockFactory::class);
    $blockModel = $blockFactory->create($name, $data);
    /** @var ThemeServiceInterface */
    $themeService = Container::get(ThemeServiceInterface::class);
    $blockHtmlPath = $themeService->getBlockHtmlPath(
        $pageModel->theme, $blockModel
    );
    if (!file_exists($blockHtmlPath)) {
        throw new \Exception("Block not found: " . $blockHtmlPath);
    }
    $blockRegistry->register($blockModel);
    $block = $data;
    $block['id'] = $blockModel->id;
    include $blockHtmlPath;
}

/**
 * Outputs a snippet of HTML.
 * @param string $snippetName The name of the snippet including the namespace.
 * @param array $data The data to be passed to the snippet.
 * @throws \Exception if the snippet HTML file does not exist.
 * @return void
 */
function snippet(string $snippetName, array $data = []){
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    $themeModel = $pageModel->theme;
    /** @var ThemeServiceInterface */
    $themeService = Container::get(ThemeServiceInterface::class);
    $snippetPath = $themeService->getSnippetHtmlPath(
        $themeModel, $snippetName
    );
    if (!file_exists($snippetPath)) {
        throw new \Exception("Snippet not found: " . $snippetPath);
    }
    $snippet = $data;
    include $snippetPath;
}

/**
 * Retrieves data from the page model.
 * This function returns the value of a specific field from the page data.
 * If the field does not exist, it returns null.
 *
 * @param string $field The field to retrieve from the page data.
 * @return mixed The value of the field or null if it does not exist.
 */
function page_data(string $field): mixed {
    /** @var OutputBufferContextManager */
    $outputBufferCtxManager = Container::get(OutputBufferContextManager::class);
    $pageModel = $outputBufferCtxManager->get()->pageModel;
    $pageData = $pageModel->data;
    return $pageData[$field] ?? null;
}

/**
 * Outputs the base URL of the application.
 * The base URL can be set in the configuration.
 * @return string
 */
function base_url(): string {
    $configuration = Container::get(ConfigurationInterface::class);
    return $configuration->getBaseUrl();
}
