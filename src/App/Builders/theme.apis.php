<?php 

use Kenjiefx\ScratchPHP\App\Blocks\BlockFactory;
use Kenjiefx\ScratchPHP\App\Blocks\BlockService;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessageBoard;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessage;
use Kenjiefx\ScratchPHP\App\Builders\BuildMessageChannel;
use Kenjiefx\ScratchPHP\App\Components\ComponentService;
use Kenjiefx\ScratchPHP\App\Exports\ExportFactory;
use Kenjiefx\ScratchPHP\Container;
use Kenjiefx\ScratchPHP\App\Components\ComponentModel;
use Kenjiefx\ScratchPHP\App\Components\ComponentFactory;

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
    echo '<!--start:template_assets-->' . PHP_EOL;
    echo '<script type="text/javascript" src="'.$jsExportModel->relativePath.'?v='.time().'"></script>'.PHP_EOL;
    echo '<link rel="stylesheet" href="'.$cssExportModel->relativePath.'?v='.time().'">';
    echo '<!--end:template_assets-->' . PHP_EOL;
}

function template_content(){
    BuildMessageChannel::post(
        BuildMessage::TEMPLATE_RENDER
    );
}

/** 
 * Renders a component into templates or other components.
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
    include $componentPath->path;
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

function snippet(){
    
}