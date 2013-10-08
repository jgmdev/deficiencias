<?php

/**
 * @author Jefferson González
 * @license MIT
 */

namespace Cms;

/**
 * Function to manage files and directories
 */
class FileSystem {

    /**
     * Disable constructor
     */
    private function __construct(){}

    /**
     * Moves a file to another path and renames it if already a file with the same name
     * exist. Also strips any special characters. @see rename_file_if_exist()
     * @param string $source The file to move.
     * @param string $destination The new path of the file.
     * @return string File name of the file moved with the path stripped.
     * @throws \Cms\Exception\FileSystem\MoveFileException
     */
    public static function MoveFile($source, $destination) {
        //Strip any special characters from filename
        $name = explode("/", $destination);
        $file_name = $name[count($name) - 1];
        $extension = "";
        $file_name_no_extension = text_to_uri(self::StripFileExtension($file_name, $extension));
        $name[count($name) - 1] = $file_name_no_extension . "." . $extension;
        $destination = implode("/", $name);

        $destination = self::RenameIfExists($destination);

        if (!rename($source, $destination)) {
            throw new \Cms\Exception\FileSystem\MoveFileException;
        }

        $name = explode("/", $destination);

        return $name[count($name) - 1];
    }

    /**
     * Check if a filename or directory already exist and generates a new one with a
     * number appended. For example if /home/test/text.txt exist
     * returns /home/test/text-0.txt
     * @param string $path The full file path to check for existence.
     * @return string The path renamed if exist or the same file name.
     */
    public static function RenameIfExists($path) {
        $file_index = 0;

        //Check if the file already exist and appends an index
        //on it to not overwrite  the existing one.
        while (file_exists($path)) {
            $segments = explode("/", $path);

            $filename_segments = explode(".", $segments[count($segments) - 1]);

            if (count($filename_segments) > 1) {
                $ext = "." . $filename_segments[count($filename_segments) - 1];
            } else {
                $ext = "";
            }

            $filename = "";

            for ($i = 0; $i < count($segments) - 1; $i++) {
                $filename .= $segments[$i] . "/";
            }

            if (count($filename_segments) == 1) {
                $filename .= $segments[count($segments) - 1];
            }

            for ($i = 0; $i < count($filename_segments) - 1; $i++) {
                $filename .= $filename_segments[$i];

                if ($i != count($filename_segments) - 2) {
                    $filename .= ".";
                }
            }

            $temp_destination_check = $filename . "-" . $file_index . $ext;
            if (file_exists($temp_destination_check)) {
                $file_index++;
            } else {
                $path = $temp_destination_check;
            }
        }

        return $path;
    }

    /**
     * Get all the files and directories available on a specified path.
     * @param string $path
     * @return array List of files found.
     */
    public static function GetDirContent($path) {
        $files = array();
        $directory = opendir($path);

        while (($file = readdir($directory)) !== false) {
            $full_path = $path . "/" . $file;

            if (is_file($full_path)) {
                $files[] = $full_path;
            } elseif ($file != "." && $file != ".." && is_dir($full_path)) {
                $files[] = $full_path;
                $files = array_merge($files, self::GetDirContent($full_path));
            }
        }

        closedir($directory);

        return $files;
    }

    /**
     * Same as php mkdir() but adds Operating system check and replaces
     * every / by \ on windows.
     * @param string $directory The directory to create.
     * @param integer $mode the permissions granted to the directory.
     * @param bool $recursive Recurse in to the path creating neccesary directories.
     * @return bool true on success false on fail.
     */
    public static function MakeDir($directory, $mode = 0755, $recursive = false) {
        if ("" . strpos(PHP_OS, "WIN") . "" != "") {
            $directory = str_replace("/", "\\", $directory);
        }

        return @ mkdir($directory, $mode, $recursive);
    }

    /**
     * Moves a directory and its content by renaming it to another directory even
     * if already exist, mergin the content of the source directory to the target
     * directory and replacing files.
     * @param string $source The dirctory to rename.
     * @param string $target The target path of the source directory.
     * @return bool true on success or false on fail.
     */
    public static function RecursiveMoveDir($source, $target) {
        $source_dir = opendir($source);

        while (($item = readdir($source_dir)) !== false) {
            $source_full_path = $source . "/" . $item;
            $target_full_path = $target . "/" . $item;

            if ($item != "." && $item != "..") {
                //Replace any existing file with source one
                if (is_file($source_full_path)) {
                    //Replace existing target file with source file
                    if (file_exists($target_full_path)) {
                        //Remove target file before replacing
                        if (!unlink($target_full_path)) {
                            return false;
                        }
                    }

                    //Move source file to target path
                    if (!rename($source_full_path, $target_full_path)) {
                        return false;
                    }
                } else if (is_dir($source_full_path)) {
                    //If directory already exist just replace its content
                    if (file_exists($target_full_path)) {
                        self::RecursiveMoveDir($source_full_path, $target_full_path);
                    } else {
                        //If directory doesnt exist just move source directory to target path
                        if (!rename($source_full_path, $target_full_path)) {
                            return false;
                        }
                    }
                }
            }
        }

        closedir($source_dir);

        return true;
    }

    /**
     * Copy a directory and its content to another directory replacing any file
     * on the target directory if already exist.
     * @param string $source The directory to copy.
     * @param string $target The copy destination.
     * @return bool true on success or false on fail.
     */
    public static function RecursiveCopyDir($source, $target) {
        $source_dir = opendir($source);

        //Check if source directory exists
        if (!$source_dir) {
            return false;
        }

        //Create target directory in case it doesnt exist
        if (!file_exists($target)) {
            self::MakeDir($target, 0755, true);
        }

        while (($item = readdir($source_dir)) !== false) {
            $source_full_path = $source . "/" . $item;
            $target_full_path = $target . "/" . $item;

            if ($item != "." && $item != "..") {
                //copy source files
                if (is_file($source_full_path)) {
                    if (!copy($source_full_path, $target_full_path)) {
                        return false;
                    }
                } else if (is_dir($source_full_path)) {
                    self::RecursiveCopyDir($source_full_path, $target_full_path);
                }
            }
        }

        closedir($source_dir);

        return true;
    }

    /**
     * Remove a directory that is not empty by deleting all its content.
     * @param string $directory The directory to delete with all its content.
     * @param string $empty Removes all directory contents keeping only itself.
     * @return bool True on success or false.
     */
    public static function RecursiveRemoveDir($directory, $empty = false) {
        // if the path has a slash at the end we remove it here
        if (substr($directory, -1) == '/') {
            $directory = substr($directory, 0, -1);
        }

        // if the path is not valid or is not a directory ...
        if (!file_exists($directory) || !is_dir($directory)) {
            throw new Exceptions\FileSystem\InvalidDirectoryException;

            // ... if the path is not readable
        } elseif (!is_readable($directory)) {
            throw new Exceptions\FileSystem\WriteFileException;
        } else {
            $handle = opendir($directory);

            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    // we build the new path to delete
                    $path = $directory . '/' . $item;

                    // if the new path is a directory
                    if (is_dir($path)) {
                        self::RecursiveRemoveDir($path);

                        // if the new path is a file
                    } else {
                        if (!unlink($path)) {
                            return false;
                        }
                    }
                }
            }

            closedir($handle);

            if ($empty == false) {
                if (!rmdir($directory)) {
                    return false;
                }
            }

            return true;
        }
    }

    /**
     * Removes the extension from a file name
     * @param string $filename The name or path of the file
     * @param reference A reference to a variable to store the file extension.
     * @return string The file name with the extension stripped out.
     */
    public static function StripFileExtension($filename, &$extension = null) {
        $file_array = explode(".", $filename);

        $extension = $file_array[count($file_array) - 1];

        unset($file_array[count($file_array) - 1]);

        $filename = implode("", $file_array);

        return $filename;
    }

}

?>
