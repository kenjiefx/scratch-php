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
        // Remove template asset references from the HTML content
        $htmlContent = $event->getContent();
        $updatedHtml = preg_replace(
            '/<!--start:template_assets-->.*?<!--end:template_assets-->/is',
            '',
            $htmlContent
        );
        $event->updateContent($updatedHtml);
    }
}