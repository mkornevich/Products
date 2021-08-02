<?php


namespace App\Command\ProductsImportCommand;


interface FilesystemInterface
{
    /**
     * Reads entire file into a string
     * @link https://php.net/manual/en/function.file-get-contents.php
     * @param string $filename <p>
     * Name of the file to read.
     * </p>
     * @param bool $use_include_path [optional] <p>
     * Note: As of PHP 5 the FILE_USE_INCLUDE_PATH constant can be
     * used to trigger include path search.
     * </p>
     * @param resource $context [optional] <p>
     * A valid context resource created with
     * stream_context_create. If you don't need to use a
     * custom context, you can skip this parameter by null.
     * </p>
     * @param int $offset [optional] <p>
     * The offset where the reading starts.
     * </p>
     * @param int|null $length [optional] <p>
     * Maximum length of data read. The default is to read until end
     * of file is reached.
     * </p>
     * @return string|false The function returns the read data or false on failure.
     */
    public function fileGetContents(string $filename, bool $use_include_path = false, $context, int $offset = 0, ?int $length): string|false;

    /**
     * Checks whether a file or directory exists
     * @link https://php.net/manual/en/function.file-exists.php
     * @param string $filename <p>
     * Path to the file or directory.
     * </p>
     * <p>
     * On windows, use //computername/share/filename or
     * \\computername\share\filename to check files on
     * network shares.
     * </p>
     * @return bool true if the file or directory specified by
     * filename exists; false otherwise.
     * </p>
     * <p>
     * This function will return false for symlinks pointing to non-existing
     * files.
     * </p>
     * <p>
     * This function returns false for files inaccessible due to safe mode restrictions. However these
     * files still can be included if
     * they are located in safe_mode_include_dir.
     * </p>
     * <p>
     * The check is done using the real UID/GID instead of the effective one.
     */
    public function fileExists(string $filename): bool;
}