<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor\Extension;

use Kenjiefx\ScratchPHP\App\Events\Instances\PageHTMLBuildCompleteEvent;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionInterface;

class EditorExtension implements ExtensionInterface {
    
    /** 
     * Editor interface will need to load the assets differently. 
     * This is because the editor is not a page, but rather a 
     * component that is loaded into the page.
     */
    #[ListensTo(PageHTMLBuildCompleteEvent::class)]
    public function replaceAssetSrcs(PageHTMLBuildCompleteEvent $event): void {
        // Replace the template asset references with the editor assets.
        $htmlContent = $event->content;
        $uniqId = uniqid();
        $editorAssets = '<link rel="stylesheet" href="$assets/editor.css?v='.$uniqId.'">' .
                        '<script type="text/javascript" src="$assets/editor.js?v='.$uniqId.'"></script>';
        $updatedHtml = str_replace(
            '<!--start:template_assets-->',
            $editorAssets . '<!--start:template_assets-->',
            $htmlContent
        );
        $event->content = $updatedHtml;
    }

}