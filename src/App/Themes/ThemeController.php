<?php

namespace Kenjiefx\ScratchPHP\App\Themes;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;

class ThemeController
{
    private static ThemeModel $themeModel;

    private string $themeLibPath = ROOT.'/theme/';

    public function useTheme(
        string $themeName
    ){
        if (!isset(static::$themeModel)) {
            $themeDirPath = $this->themeLibPath.$themeName;
            if (!is_dir($themeDirPath)) {
                $error = 'Theme Not Found! Your scratch.config.json declares to use the theme ';
                $error .= 'named "'.$themeName.'", but the theme does not exist in this path: '.$themeDirPath;
                throw new ConfigurationException($error);
            }
            static::$themeModel = new ThemeModel(
                name: $themeName,
                dirPath: $themeDirPath
            );
        }
    }

    public function getTemplatePath(
        string $templateName
    ){
        return $this->getThemePath().
            '/templates/template.'.$templateName.'.php';
    }

    public function getComponentDir(
        string $componentName
    ){
        return $this->getThemePath().
            '/components/'.$componentName;
    }

    public function getIndexPath(){
        return $this->getThemePath().'/index.php';
    }

    public function getSnippetPath(
        string $snippetName
    ){
        return $this->getThemePath()
            .'/snippets/'.$snippetName.'.php';
    }

    public function getAssetsDir(){
        return $this->getThemePath().'/assets';
    }

    public function getThemePath(){
        return static::$themeModel->getDirPath();
    }

    public function createTheme(
        string $themeName
    ){
        $themeDirPath = $this->themeLibPath.$themeName;
        if (is_dir($themeDirPath)) {
            $error = 'Theme Already Exists! The theme you are trying to create named "';
            $error .= $themeDirPath.'" already exists.';
            throw new \Exception($error);
        }
        mkdir($themeDirPath);
        $this->copyBin(__dir__.'/bin',$themeDirPath);
    }

    private function copyBin(
        string $sourcePath,
        string $destinationPath
    ){
        foreach (scandir($sourcePath) as $fileName) {
            if ($fileName==='.'||$fileName==='..') continue;
            $pathAsSource = $sourcePath.'/'.$fileName;
            $pathAsDestination = $destinationPath.'/'.$fileName;
            if (is_dir($pathAsSource)) {
                mkdir($pathAsDestination);
                $this->copyBin($pathAsSource,$pathAsDestination);
            } else {
                copy($pathAsSource,$pathAsDestination);
            }
        }
    }
}
