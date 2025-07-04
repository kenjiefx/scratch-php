<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;
use Kenjiefx\ScratchPHP\App\Events\HTMLBuildCompletedEvent;
use Kenjiefx\ScratchPHP\App\Events\ListensTo;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsInterface;

class EditorExtension implements ExtensionsInterface
{   
    /** 
     * Editor interface will need to load the assets differently. 
     * This is because the editor is not a page, but rather a 
     * component that is loaded into the page.
     */
    #[ListensTo(HTMLBuildCompletedEvent::class)]
    public function removeTemplateAssetReferences(HTMLBuildCompletedEvent $event)
    {
        // Replace the template asset references with the editor assets.
        $htmlContent = $event->getContent();
        $pattern = '/<!--start:template_assets-->(.*?)<!--end:template_assets-->/s';
        $editorAssets = '<!--start:template_assets-->' .
            '<link rel="stylesheet" href="$assets/editor.css">' .
            '<script type="text/javascript" src="$assets/editor.js"></script>' .
            '<!--end:template_assets-->';
        $updatedHtml = preg_replace($pattern, $editorAssets, $htmlContent);
        $event->updateContent($updatedHtml);
    }
}