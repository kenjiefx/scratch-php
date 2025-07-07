<?php 

namespace Kenjiefx\ScratchPHP\App\Exports;

/**
 * Allows different export strategies to implement their own export logic.
 */
interface ExporterInterface {

    /**
     * Exports data to a file. This can be used by different exporters 
     * to implement their own export logic.
     *
     * @param ExportModel $export The export model containing file and content.
     * @return void
     */
    public function export(ExportModel $export): void;

}