<?php 

namespace Kenjiefx\ScratchPHP\App\HTTP\Editor;

use Kenjiefx\ScratchPHP\App\Exports\ExporterInterface;
use Kenjiefx\ScratchPHP\App\Exports\ExportModel;

class EditorExportService implements ExporterInterface {

    public function export(ExportModel $exportModel): void {
        $content = $exportModel->content;
        $filePath = $exportModel->relativePath;
        $fileNameWithExtension = basename($filePath);
        $exportPath = __dir__ . '/exports/';
        // Check if file is an HTML file 
        if (pathinfo($fileNameWithExtension, PATHINFO_EXTENSION) === 'html') {
            $exportPath = $exportPath . 'page.html';
        } elseif (pathinfo($fileNameWithExtension, PATHINFO_EXTENSION) === 'css') {
            $exportPath = $exportPath . 'style.css';
        } elseif (pathinfo($fileNameWithExtension, PATHINFO_EXTENSION) === 'js') {
            $exportPath = $exportPath . 'script.js';
        } else {
            $exportPath = $exportPath . $fileNameWithExtension;
        }
        file_put_contents($exportPath, $content);
    }

}