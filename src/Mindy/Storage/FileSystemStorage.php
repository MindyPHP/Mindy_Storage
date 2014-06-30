<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 25/06/14.06.2014 11:39
 */

namespace Mindy\Storage;


use FilesystemIterator;
use Mindy\Base\Exception\Exception;
use Mindy\Helper\Alias;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class FileSystemStorage extends Storage
{
    /**
     * @var string
     */
    public $folderName = 'public';
    /**
     * @var string
     */
    public $location = '';
    /**
     * @var string
     */
    public $baseUrl = '/media/';

    public function init()
    {
        $this->location = Alias::get("www." . $this->folderName);
        if (!is_dir($this->location)) {
            throw new Exception("Directory not found.");
        }
        $this->location = realpath(rtrim($this->location, DIRECTORY_SEPARATOR));
    }

    public function size($name)
    {
        return filesize($this->path($name));
    }

    protected function openInternal($name, $mode)
    {
        if (!$this->exists($name)) {
            return null;
        }

        $path = $this->path($name);
        $handle = fopen($path, $mode, false);
        $contents = fread($handle, filesize($path));
        fclose($handle);
        return $contents;
    }

    public function path($name)
    {
        return realpath($this->location . DIRECTORY_SEPARATOR . $name);
    }

    protected function saveInternal($name, $content)
    {
        $directory = dirname($name);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        return file_put_contents($name, $content) !== false;
    }

    public function delete($name)
    {
        $path = $this->path($name);
        if (is_file($path)) {
            unlink($path);
        } else if (is_dir($path)) {
            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($name, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $iterPath) {
                if ($iterPath->isDir()) {
                    rmdir($iterPath->getPathname());
                } else {
                    unlink($iterPath->getPathname());
                }
            }
            rmdir($path);
        }
    }

    public function url($name)
    {
        return $this->baseUrl . str_replace('\\', '/', $name);
    }

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return bool
     */
    public function exists($name)
    {
        return is_file($this->path($name));
    }

    /**
     * @param $name
     * @return int
     */
    public function accessedTime($name)
    {
        return fileatime($this->path($name));
    }

    /**
     * @param $name
     * @return int
     */
    public function createdTime($name)
    {
        return filectime($this->path($name));
    }

    /**
     * @param $name
     * @return int
     */
    public function modifiedTime($name)
    {
        return filemtime($this->path($name));
    }
}
