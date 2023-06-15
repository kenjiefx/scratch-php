<?php

namespace Kenjiefx\ScratchPHP\App\Helpers;

class FilesCopier
{
    public static function copyDir(string $sourceDir, string $destDir){
        foreach (scandir($sourceDir) as $fileName) {
            if ($fileName==='.'||$fileName==='..') continue;
            $sourcePath      = $sourceDir.'/'.$fileName;
            $destinationPath = $destDir.'/'.$fileName;
            if (is_dir($sourcePath)) {
                mkdir($destinationPath);
                self::copyDir($sourcePath, $destinationPath);
                continue;
            } 
            copy($sourcePath,$destinationPath);
        }
    }
}
