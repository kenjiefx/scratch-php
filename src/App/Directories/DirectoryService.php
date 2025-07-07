<?php 

namespace Kenjiefx\ScratchPHP\App\Directories;

class DirectoryService {

    public function __construct(

    ) {}

    /**
     * Checks if a directory exists.
     *
     * @param string $path The path to the directory.
     * @return bool True if the directory exists, false otherwise.
     */
    public function exists(string $path): bool {
        return is_dir($path);
    }

    /**
     * Creates a directory.
     *
     * @param string $path The path to the directory.
     * @return bool True if the directory was created successfully, false otherwise.
     */
    public function create(string $path): bool {
        if ($this->exists($path)) {
            return false; // Directory already exists
        }
        return mkdir($path, 0755, true); // Create directory with permissions
    }

    /**
     * Deletes a directory.
     *
     * @param string $path The path to the directory.
     * @return bool True if the directory was deleted successfully, false otherwise.
     */
    public function delete(string $path): bool {
        if (!$this->exists($path)) {
            return false; // Directory does not exist
        }
        return rmdir($path); // Remove directory
    }

    /**
     * Gets the list of files in a directory.
     *
     * @param string $path The path to the directory.
     * @return array An array of file names in the directory.
     */
    public function listFiles(string $path): array {
        if (!$this->exists($path)) {
            return []; // Directory does not exist
        }
        $files = scandir($path);
        return array_diff($files, ['.', '..']); // Remove '.' and '..' from the list
    }

    /**
     * Gets the size of a directory.
     *
     * @param string $path The path to the directory.
     * @return int The size of the directory in bytes.
     */
    public function getSize(string $path): int {
        if (!$this->exists($path)) {
            return 0; // Directory does not exist
        }
        $size = 0;
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));
        foreach ($files as $file) {
            if ($file->isFile()) {
                $size += $file->getSize(); // Add file size to total
            }
        }
        return $size; // Return total size
    }

    /**
     * Copies a directory to a new location.
     *
     * @param string $source The path to the source directory.
     * @param string $destination The path to the destination directory.
     * @return bool True if the directory was copied successfully, false otherwise.
     */
    public function copy(string $source, string $destination): bool {
        if (!$this->exists($source)) {
            return false; // Source directory does not exist
        }
        if ($this->exists($destination)) {
            return false; // Destination directory already exists
        }
        $this->create($destination); // Create destination directory
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $srcFile = $source . DIRECTORY_SEPARATOR . $file;
                $destFile = $destination . DIRECTORY_SEPARATOR . $file;
                if (is_dir($srcFile)) {
                    $this->copy($srcFile, $destFile); // Recursively copy directories
                } else {
                    copy($srcFile, $destFile); // Copy files
                }
            }
        }
        return true; // Return success
    }

    public function isDirectory(string $path): bool {
        return is_dir($path);
    }

}