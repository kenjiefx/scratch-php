<?php

namespace Kenjiefx\ScratchPHP\App\Themes;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Exceptions\ThemeNotFoundException;

/**
 * Manages the functionality of the Theme Model. It perform actions such as retrieving theme settings, 
 * applying themes to different components or pages, and managing theme-related operations.
 */
class ThemeController
{

    /**
     * During the build process, there is only one (1) Theme Model that
     * is re-used through each of the build stages. This Theme Model can
     * and should be accessed only through the Theme Controller.
     */
    private static ThemeModel $ThemeModel;

    /**
     * This is the path to the Theme Library. The theme library is a collection
     * of themes. By default, themes are stored in ROOT.'/theme' directory of 
     * your project.
     */
    private string $theme_lib_path = ROOT.'/theme/';

    /**
     * This function decides the theme that would be used throughout the build
     * process. While this method can be called multiple times, it will only
     * instantiates the ThemeModel once.  
     */
    public function mount_theme(string $theme_name){
        if (!isset(static::$ThemeModel)) {
            $path_to_theme_directory = $this->theme_lib_path.$theme_name;
            if (!is_dir($path_to_theme_directory)) {
                new ThemeNotFoundException($theme_name, $path_to_theme_directory);
            }
            static::$ThemeModel = new ThemeModel($theme_name, $path_to_theme_directory);
        }
    }
    
    /**
     * Returns the directory where Theme templates are stored.
     */
    public function get_template_dir_path(string $template_name){
        return $this->get_theme_dir_path().
            '/templates/template.'.$template_name.'.php';
    }

    /**
     * Returns the directory where Theme components are stored.
     */
    public function get_component_dir_path(string $component_namde){
        return $this->get_theme_dir_path().
            '/components/'.$component_namde;
    }

    /**
     * Returns the directory where Theme index file is stored.
     */
    public function get_index_file_path(){
        return $this->get_theme_dir_path().'/index.php';
    }

    /**
     * Returns the directory where the Theme snippets are stored.
     */
    public function get_snippet_dir_path(string $snippet_name){
        return $this->get_theme_dir_path()
            .'/snippets/'.$snippet_name.'.php';
    }

    /**
     * Returns the directory where Theme asset files are stored
     */
    public function get_assets_dir_path(){
        return $this->get_theme_dir_path().'/assets';
    }

    /** 
     * Returns the directory path of the mounted theme.
     */
    public function get_theme_dir_path(){
        return static::$ThemeModel->get_dir_path();
    }

    /**
     * Creates a theme
     */
    public function create_theme(string $theme_name){
        $theme_dir_path = $this->theme_lib_path.$theme_name;
        if (is_dir($theme_dir_path)) {
            $error  = 'Theme Already Exists! The theme you are trying to create named "';
            $error .= $theme_dir_path.'" already exists.';
            throw new \Exception($error);
        }
        mkdir($theme_dir_path);
        $this->copy_default_theme_files(__dir__.'/bin',$theme_dir_path);
    }

    private function copy_default_theme_files(string $source_dir_path, string $destination_dir_path){
        foreach (scandir($source_dir_path) as $file_name) {
            if ($file_name==='.'||$file_name==='..') continue;
            $source_file_path      = $source_dir_path.'/'.$file_name;
            $destination_file_path = $destination_dir_path.'/'.$file_name;
            if (is_dir($source_file_path)) {
                mkdir($destination_file_path);
                $this->copy_default_theme_files ($source_file_path, $destination_file_path);
            } else {
                copy($source_file_path, $destination_file_path);
            }
        }
    }
}
