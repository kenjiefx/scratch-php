<?php

namespace Kenjiefx\ScratchPHP\App\Utils;

enum MimeType: string
{
    case JPG = 'image/jpeg';
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case SVG = 'image/svg+xml';
    case PDF = 'application/pdf';
    case TXT = 'text/plain';
    case HTML = 'text/html';
    case CSS = 'text/css';
    case JS = 'application/javascript';
    case JSON = 'application/json';
    case XML = 'application/xml';
    case CSV = 'text/csv';
    case MP4 = 'video/mp4';
    case MP3 = 'audio/mpeg';
    case ZIP = 'application/zip';
    case ICO = 'image/x-icon';

    public static function fromExtension(string $extension): ?self
    {
        $extension = strtoupper(ltrim($extension, '.'));

        return self::tryFrom($extension);
    }
}
