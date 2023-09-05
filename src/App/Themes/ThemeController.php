<?php

namespace Kenjiefx\ScratchPHP\App\Themes;
use Kenjiefx\ScratchPHP\App\Events\EventDispatcher;
use Kenjiefx\ScratchPHP\App\Events\OnCreateThemeEvent;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Exceptions\ThemeAlreadyExistsException;
use Kenjiefx\ScratchPHP\App\Exceptions\ThemeNotFoundException;
use Kenjiefx\ScratchPHP\App\Helpers\FilesCopier;

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
    private const THEME_LIB_PATH = '/theme/';

    /**
     * This function decides the theme that would be used throughout the build
     * process. While this method can be called multiple times, it will only
     * instantiates the ThemeModel once.  
     */
    public function mount(string $name){
        if (!isset(static::$ThemeModel)) {
            $path = ROOT.self::THEME_LIB_PATH.$name;
            if (!is_dir($path)) {
                new ThemeNotFoundException($name,$path);
            }
            static::$ThemeModel = new ThemeModel($name,$path);
        }
    }
    
    /** Returns the file path of a certain template in the theme. */
    public function getTemplateFilePath(string $name){
        return $this->getThemeDirPath().'/templates/template.'.$name.'.php';
    }

    /** Returns the directory where Theme components are stored. */
    public function getComponentsDirPath(string $name){
        return $this->getThemeDirPath().'/components/'.$name;
    }

    /** Returns the file path fo the theme's index file. */
    public function getIndexFilePath(){
        return $this->getThemeDirPath().'/index.php';
    }

    /** Returns the directory where the Theme snippets are stored.*/
    public function getSnippetFilePath (string $name){
        return $this->getThemeDirPath().'/snippets/'.$name.'.php';
    }

    /** Returns the directory where Theme asset files are stored */
    public function getAssetsDirPath(){
        return $this->getThemeDirPath().'/assets';
    }

    /** Returns the directory path of the mounted theme. */
    public function getThemeDirPath(){
        return static::$ThemeModel->getDirPath();
    }

    /**  Creates a theme */
    public static function create(string $name,array $options){
        $path = ROOT.self::THEME_LIB_PATH.$name;
        if (is_dir($path)) {
            throw new ThemeAlreadyExistsException($name);
        }
        mkdir($path);

        if ($options['useScratchWelcomeTheme']) {
            FilesCopier::copyDir(__dir__.'/bin',$path);
        } else {
            FilesCopier::copyDir(__dir__.'/default',$path);
            mkdir($path.'/assets');
            mkdir($path.'/components');
            mkdir($path.'/snippets');
        }
        

        /** Dispatches event registered under OnCreateThemeEvents */
        $themeController = new ThemeController();
        $themeController->mount($name);
        $EventDispatcher = new EventDispatcher;
        $EventDispatcher->dispatchEvent(OnCreateThemeEvent::class,$themeController);
    }
}
