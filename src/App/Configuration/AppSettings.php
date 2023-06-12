<?php

namespace Kenjiefx\ScratchPHP\App\Configuration;
use Kenjiefx\ScratchPHP\App\Exceptions\ConfigurationException;
use Kenjiefx\ScratchPHP\App\Exceptions\MustImplementExtensionInterfaceException;
use Kenjiefx\ScratchPHP\App\Extensions\ExtensionsRegistry;
use Kenjiefx\ScratchPHP\App\Factory\ContainerFactory;
use Kenjiefx\ScratchPHP\App\Interfaces\ExtensionsInterface;

class AppSettings
{
    /**
     * Tells whether the configuration has been loaded 
     * already or not
     */
    private static bool $is_initialized = false;

    /**
     * The name of the strawberry configuration file 
     */
    private static string $file_name = 'scratch.config.json';

    /**
     * Contains a list of the configurable settings within
     * the framework and their values
     */
    private static array $app_configuration = [];

    /**
     * Build configuration manager
     */
    private static BuildConfiguration $BuildConfiguration; 

    private static ExtensionsRegistry $ExtensionsRegistry;

    /**
     * Loads strawberry configuration file
     */
    public static function load()
    {

        if (!static::$is_initialized) {

            # Making sure that the config json exists in the project directory
            $config_path = Self::get_path_to_config();
            if (!file_exists($config_path)) throw new ConfigurationException();

            # Next, we store the configuration data
            static::$app_configuration = Self::unpack_config_json(file_get_contents($config_path));
            
            if (isset(static::$app_configuration['build'])) {
                static::$BuildConfiguration = new BuildConfiguration(static::$app_configuration['build']);
            }

            static::$ExtensionsRegistry = new ExtensionsRegistry();
            if (isset(static::$app_configuration['extensions'])) {
                Self::mount_extensions();
            }

            static::$is_initialized = true;
        }
        
    }

    private static function unpack_config_json(string $config_json):array{
        return json_decode($config_json,TRUE);
    }

    private static function mount_extensions(){
        foreach (static::$app_configuration['extensions'] as $extension_namespace => $extension_settings) {
            $object_reflection = new \ReflectionClass($extension_namespace);

            if (!$object_reflection->implementsInterface(ExtensionsInterface::class)) {
                throw new MustImplementExtensionInterfaceException($extension_namespace);
            }
            $object_extension = ContainerFactory::create()->get($extension_namespace);
            static::$ExtensionsRegistry->add_extensions($object_extension);
        }
    }

    /**
     * Returns the path of the strawberry configuration file
     */
    private static function get_path_to_config()
    {
        return ROOT.'/'.static::$file_name;
    }

    public static function get_theme_name_from_config()
    {
        return static::$app_configuration['theme'] ?? 'slate';
    }

    public static function get_export_dir_path_from_config(){
        return static::$app_configuration['exportDir'] ?? '/dist';
    }

    public static function build()
    {
        return static::$BuildConfiguration;
    }

    public static function extensions(){
        return static::$ExtensionsRegistry;
    }

    public static function create(
        string $theme_name
    ){
        $config = [
            'theme' => $theme_name,
            'exportDir' => '/dist',
            'build' => [
                'useRandomAssetsFileNames' => false
            ],
            'extensions' => []
        ];
        $path = Self::get_path_to_config(); 
        if (!file_exists($path)) {
            file_put_contents($path,json_encode($config,JSON_PRETTY_PRINT));
            return true;
        }
        return false;
    }


}
