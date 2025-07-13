<?php 

use Kenjiefx\ScratchPHP\App\Blocks\BlockFactory;
use Kenjiefx\ScratchPHP\App\Blocks\BlockService;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessageBoard;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessage;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessageChannel;
use Kenjiefx\ScratchPHP\App\Components\ComponentService;
use Kenjiefx\ScratchPHP\App\Configurations\ConfigurationInterface;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Exports\ExportFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeFactory;
use Kenjiefx\ScratchPHP\App\Themes\ThemeModel;
use Kenjiefx\ScratchPHP\App\Themes\ThemeService;
use Kenjiefx\ScratchPHP\Container;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;
use Kenjiefx\ScratchPHP\App\Events\ComponentHTMLCollectedEvent;
use Kenjiefx\ScratchPHP\App\Statics\StaticAssetsFactory;

function page_title() {
    $pageModel = BuildMessageChannel::post(
        BuildMessage::GET_PAGE
    );
    echo $pageModel->title;
}

/**
 * Embeds CSS and Javascript for each of the pages. 
 * This function retrieves the page's relative path and 
 * the asset reference name to construct the assets'
 * relative directory path.
 */
function template_assets(){
    $pageModel = BuildMessageChannel::post(
        BuildMessage::GET_PAGE
    );
    $cssExportModel = Container::get()->get(ExportFactory::class)
        ->createAsAsset($pageModel, 'css', '');
    $jsExportModel = Container::get()->get(ExportFactory::class)
        ->createAsAsset($pageModel, 'js', '');
    $baseUrl = base_url();
    echo '<!--start:template_assets-->' . PHP_EOL;
    echo '<script type="text/javascript" src="'.$baseUrl.$jsExportModel->relativePath.'?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$baseUrl.$cssExportModel->relativePath.'?v='.time().'">';
    echo '<!--end:template_assets-->' . PHP_EOL;
}

function asset(string $fileName) {
    $staticAssetsRegistry = BuildMessageChannel::post(
        BuildMessage::GET_STATIC_ASSETS_REGISTRY
    );
    $staticAssetsFactory = Container::get()->get(StaticAssetsFactory::class);
    $staticAssetsModel = $staticAssetsFactory->create($fileName);
    $staticAssetsRegistry->register($staticAssetsModel);
    $baseUrl = base_url();
    echo "{$baseUrl}assets/{$fileName}?v=" . time();
}

function template_content(){
    BuildMessageChannel::post(
        BuildMessage::TEMPLATE_RENDER
    );
}

/**
 * This function is used to render a component.
 * It will include the component's HTML file and 
 * register the component in the component registry.
 * 
 * @param string $path The path to the component.
 * @param array $data The data to be passed to the component.
 */
function component($path, array $data = []) {
    $componentService = Container::get()->get(ComponentService::class);
    $themeModel 
        = BuildMessageChannel::post(
            BuildMessage::GET_THEME
        );
    $componentModel = ComponentFactory::create($path);
    $componentPath 
        = $componentService->getHtmlPath(
            $componentModel,
            $themeModel
        );
    $componentRegistry = BuildMessageChannel::post(
        BuildMessage::GET_COMPONENT_REGISTRY
    );
    /**
     * Components used within the theme will be registered 
     * in the component registry.
     */
    $componentRegistry->register($componentModel);
    $component = $data;
    $component['id'] = $componentModel->id;
    ob_start();
    include $componentPath->path;
    $content = ob_get_contents();
    ob_end_clean();
    $themeModel = get_theme();
    $eventDispatcher = Container::get()->get(EventDispatcher::class);
    $event = new ComponentHTMLCollectedEvent([
        "model" => $componentModel,
        "dir" => $componentService->getComponentDir($componentModel, $themeModel),
        "content" => $content,
        "data" => $data
    ]);
    $eventDispatcher->dispatchEvent($event);
    echo $event->getContent();
}

function block($path, array $data = []) {
    $blockService = Container::get()->get(BlockService::class);
    $themeModel 
        = BuildMessageChannel::post(
            BuildMessage::GET_THEME
        );
    $blockModel = BlockFactory::create($path);
    $blockPath 
        = $blockService->getHtmlPath(
            $blockModel,
            $themeModel
        );
    $blockRegistry = BuildMessageChannel::post(
        BuildMessage::GET_BLOCK_REGISTRY
    );
    /**
     * Blocks used within the theme will be registered 
     * in the block registry.
     */
    $blockRegistry->register($blockModel);
    $block = $data;
    $block['id'] = $blockModel->id;
    include $blockPath->path;
}

function get_theme(): ThemeModel {
    $configuration = Container::get()->get(ConfigurationInterface::class);
    $themeFactory = Container::get()->get(ThemeFactory::class);
    return $themeFactory->create(
        $configuration->getThemeName()
    );
}

function snippet(string $snippetName, array $data = []){ 
    $configuration = Container::get()->get(ConfigurationInterface::class);
    $themeFactory = Container::get()->get(ThemeFactory::class);
    $themeModel = $themeFactory->create(
        $configuration->getThemeName()
    );
    $themeService = Container::get()->get(ThemeService::class);
    $themeDir = $themeService->getThemeDir($themeModel);
    $snippetPath = "$themeDir/snippets/$snippetName.php";
    if (!file_exists($snippetPath)) {
        throw new \Exception("Snippet not found: $snippetName at $snippetPath");
    }
    $snippet = $data;
    include $snippetPath;
}

function page_data(string $field){
    $pageModel = BuildMessageChannel::post(
        BuildMessage::GET_PAGE
    );
    $pageData = $pageModel->data;
    return $pageData[$field] ?? null;
}

function base_url(): string {
    $configuration = Container::get()->get(ConfigurationInterface::class);
    return $configuration->getBaseUrl();
}