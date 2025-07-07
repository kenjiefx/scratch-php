<?php 

namespace Kenjiefx\ScratchPHP\App\Builders;

/**
 * AssetsEnum is an enumeration class that defines constants for various asset types.
 * These constants can be used to identify different types of assets in the theme.
 */
enum AssetsEnum: string {
    case CSS = 'css';
    case JS = 'js';
    case HTML = 'html';
    case IMAGE = 'image';
    case FONT = 'font';
    case VIDEO = 'video';
    case AUDIO = 'audio';
    case OTHER = 'other';

    /**
     * Returns the file extension associated with the asset type.
     *
     * @return string The file extension for the asset type.
     */
    public function getExtension(): string {
        return match ($this) {
            self::CSS => '.css',
            self::JS => '.js',
            self::HTML => '.html',
            self::IMAGE => '.png', // Default to PNG for images
            self::FONT => '.woff2', // Default to WOFF2 for fonts
            self::VIDEO => '.mp4', // Default to MP4 for videos
            self::AUDIO => '.mp3', // Default to MP3 for audio
            self::OTHER => '',
        };
    }
}