<?php 

namespace Kenjiefx\ScratchPHP\App\Files;
use Kenjiefx\ScratchPHP\App\Files\File;

/**
 * A wrapper to PHP's file functions.
 */
class FileService {

    /**
     * Reads the content of a file.
     *
     * @param File $file The path to the file.
     * @return string The content of the file.
     */
    public function readFile(File $file): string {
        if (!file_exists($file->path)) {
            throw new \Exception("File not found: " . $file->path);
        }
        return file_get_contents($file->path);
    }

    /**
     * Writes content to a file.
     *
     * @param File $file The path to the file.
     * @param string $content The content to write to the file.
     */
    public function writeFile(File $file, string $content): void {
        file_put_contents($file->path, $content);
    }

    /**
     * Checks if a file exists.
     *
     * @param File $file The path to the file.
     * @return bool True if the file exists, false otherwise.
     */
    public function fileExists(File $file): bool {
        return file_exists($file->path);
    }

    /**
     * Deletes a file.
     *
     * @param File $file The path to the file.
     */
    public function deleteFile(File $file): void {
        if (!file_exists($file->path)) {
            throw new \Exception("File not found: " . $file->path);
        }
        unlink($file->path);
    }

    /**
     * Gets the size of a file.
     *
     * @param File $file The path to the file.
     * @return int The size of the file in bytes.
     */
    public function getFileSize(File $file): int {
        if (!file_exists($file->path)) {
            throw new \Exception("File not found: " . $file->path);
        }
        return filesize($file->path);
    }

    /**
     * Gets the last modified time of a file.
     *
     * @param File $file The path to the file.
     * @return int The last modified time as a Unix timestamp.
     */
    public function getFileLastModified(File $file): int {
        if (!file_exists($file->path)) {
            throw new \Exception("File not found: " . $file->path);
        }
        return filemtime($file->path);
    }

    /**
     * Copies a file to a new location.
     *
     * @param File $source The path to the source file.
     * @param File $destination The path to the destination file.
     */
    public function copyFile(File $source, File $destination): void {
        if (!file_exists($source->path)) {
            throw new \Exception("Source file not found: " . $source->path);
        }
        if (!copy($source->path, $destination->path)) {
            throw new \Exception("Failed to copy file from " . $source->path . " to " . $destination->path);
        }
    }

    /**
     * Moves a file to a new location.
     *
     * @param File $source The path to the source file.
     * @param File $destination The path to the destination file.
     */
    public function moveFile(File $source, File $destination): void {
        if (!file_exists($source->path)) {
            throw new \Exception("Source file not found: " . $source->path);
        }
        if (!rename($source->path, $destination->path)) {
            throw new \Exception("Failed to move file from " . $source->path . " to " . $destination->path);
        }
    }

}