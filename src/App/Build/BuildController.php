<?php

namespace Kenjiefx\ScratchPHP\App\Build;
use Kenjiefx\ScratchPHP\App\Extensions\VentaCSS\VentaCSS;
use Kenjiefx\ScratchPHP\App\Pages\PageModel;
use Kenjiefx\ScratchPHP\App\Pages\PageRegistry;
use Kenjiefx\ScratchPHP\App\Templates\TemplateRegistry;
use Kenjiefx\ScratchPHP\App\Configuration\AppSettings;
use Kenjiefx\ScratchPHP\App\Themes\ThemeController;

/**
 * The BuildController class encapsulates various functionalities and methods necessary for the build process. 
 * It coordinates the retrieval of data from different sources, and processes them to generate static HTML pages.  
 */
class BuildController
{

    /**
     * The directory where the static HTML, Javascript and CSS are exported into. 
     * To know where or how you can define the export directory, please see 
     * `AppSettings::get_export_dir_path_from_config()` method.
     */
    private string $export_dir_path = '';

    public function __construct(
        private ThemeController $ThemeController,
        private PageRegistry $PageRegistry,
        private TemplateRegistry $TemplateRegistry, 
        private CSSController $CSSController, 
        private JSController $JSController
    ){
        AppSettings::load();
        $this->export_dir_path = ROOT.AppSettings::get_export_dir_path_from_config();
    }

    /**
     * Handles the procedural side of the build process. This method orchestrates
     * different stages of the building static contents.
     */
    public function build(){
        $this->ThemeController->mount_theme(AppSettings::get_theme_name_from_config());
        $this->PageRegistry->discover();

        $this->import_build_helper_functions();

        /**
         * We collect information as to what components and snippets 
         * each of the page templates is using
         */
        foreach ($this->PageRegistry->get_registered_pages_as_page_models() as $page_model) {
            $this->set_globals($page_model);
            $this->build_page_contents($page_model);
            $this->build_page_assets($page_model);
            $this->apply_extensions($page_model);
        }

        $this->clear_export_dir($this->export_dir_path);

        /**
         * We export the pages into the provided export directory.
         * While it may appear that the loop above is just the same
         * as with the loop below, it is intended as we need to process
         * ALL the pages and assets first before exporting, thus catching
         * errors along the way, killing the build process without really 
         * exporting any changes to the export directory. 
         */
        foreach ($this->PageRegistry->get_registered_pages_as_page_models() as $page_model) {
            $this->export_page_content($page_model);
            $this->export_page_assets($page_model);
        }

        $this->PageRegistry->clear_bin();
        $this->post_build_services();
    }

    private function build_page_contents(PageModel $page_model){
        $template_name = $page_model->get_template_name();
        $template_model = $this->TemplateRegistry->register($template_name);

        ob_start();
        include $this->ThemeController->get_index_file_path();
        $page_model->set_html(ob_get_contents());
        ob_end_clean();

        if (!$template_model->has_been_frozen()) $template_model->freeze();
    }

    public function build_page_assets(PageModel $page_model){
        /** Global CSS and JavaScript */
        $global_page_css = $this->CSSController->collect_global_css();
        $global_page_js  = $this->JSController->collect_global_js();

        /** Component CSS and Javascript */
        $template_name = $page_model->get_template_name();
        $TemplateModel = $this->TemplateRegistry->get_template_model($template_name);
        $global_page_css .= $this->CSSController->collect_component_css($TemplateModel);
        $global_page_js .= $this->JSController->collect_component_js($TemplateModel);

        $page_model->set_css($global_page_css);
        $page_model->set_javascript($global_page_js);
    }

    private function apply_extensions(PageModel $page_model){
        $template_name = $page_model->get_template_name();
        $template_model = $this->TemplateRegistry->get_template_model($template_name);

        # Extensions
        foreach (AppSettings::extensions()->get_extensions() as $extension) {
            $html_content = $extension->mutatePageHTML($page_model->get_html());
            $css_content  = $extension->mutatePageCSS($page_model->get_css());
            $js_content   = $extension->mutatePageJS($page_model->get_javascript());
            $page_model->set_html($html_content);
            $page_model->set_css($css_content);
            $page_model->set_javascript($js_content);
        }
    }

    private function export_page_content(PageModel $page_model){

        # Retrieve and create export directory, if not existing
        $page_directory = $this->export_dir_path.'/'.$page_model->get_directory_path().'/';
        if (!is_dir($page_directory)) mkdir($page_directory);

        $static_extension = (AppSettings::build()->exportPageWithoutHTMLExtension() === true) ? '' : '.html';
        $export_path      = $page_directory.$page_model->get_name().$static_extension;
        file_put_contents($export_path,$page_model->get_html());
    }

    private function export_page_assets(PageModel $page_model){

        $page_dir_path = ($page_model->get_directory_path()==='') ? '' : '/'.$page_model->get_directory_path(); 
        $assets_dir    = $this->export_dir_path.'/assets'.$page_dir_path.'/';

        if (!is_dir($assets_dir)) mkdir($assets_dir);

        $assets_file_name = (AppSettings::build()->useRandomAssetsFileNames()===true) 
            ? $page_model->get_id() : $page_model->get_name();
            
        $export_path = $assets_dir.$assets_file_name.'.css';
        file_put_contents($export_path,$page_model->get_css());

        $exportPath = $assets_dir.$assets_file_name.'.js';
        file_put_contents($exportPath,$page_model->get_javascript());
    }

    private function import_build_helper_functions(){
        include __dir__.'/build.functions.php';
    }

    private function clear_export_dir(string $directory_to_clear){
        foreach (scandir($directory_to_clear) as $file) {
            if ($file==='.'||$file==='..') continue;
            $path = $directory_to_clear.'/'.$file;
            if (is_dir($path)) {
                $this->clear_export_dir($path);
                rmdir($path);
            } else {
                unlink($path);
            }
        }
    }

    private function set_globals(PageModel $page_model){
        $GLOBALS['__page_model'] = $page_model;
    }

    public function post_build_services(){
        foreach (AppSettings::extensions()->get_extensions() as $extension) {
            if (method_exists($extension,'onBuildComplete')) {
                $extension->onBuildComplete();
            }
        }
    }
    
}
