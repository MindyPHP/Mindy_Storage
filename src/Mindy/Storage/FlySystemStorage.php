<?php
/**
 * Author: Falaleev Maxim
 * Email: max@studio107.ru
 * Company: http://en.studio107.ru
 * Date: 24/03/16
 * Time: 19:59
 */

namespace Mindy\Storage;

use Exception;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\Storage\Memory as CacheStore;
use Mindy\Helper\Alias;

class FlySystemStorage extends Filesystem
{
    /**
     * @var string
     */
    public $folderName = 'media';
    /**
     * @var string
     */
    public $baseUrl = '/media/';

    public function init()
    {
        if (empty($this->location)) {
            $this->location = Alias::get("www." . $this->folderName);
        }
        if (!is_dir($this->location)) {
            throw new Exception("Directory not found.");
        }
        $this->location = realpath(rtrim($this->location, DIRECTORY_SEPARATOR));

        $localAdapter = new Local('/path/to/root');

        // Create the cache store
        $cacheStore = new CacheStore();

        // Decorate the adapter
        $adapter = new CachedAdapter($localAdapter, $cacheStore);

        // And use that to create the file system
        $filesystem = new Filesystem($adapter);
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param string $directory
     * @param bool $recursive
     * @return array
     */
    public function dir($directory = '', $recursive = false)
    {
        return [
            'directories' => parent::listPaths($directory, $recursive),
            'files' => parent::listPaths($directory, $recursive)
        ];
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param $dirname
     * @param array $config
     * @return bool
     */
    public function mkDir($dirname, array $config = [])
    {
        return parent::createDir($dirname, $config);
    }

    protected function openInternal($name, $mode)
    {
        // TODO: Implement openInternal() method.
    }

    protected function saveInternal($name, $content)
    {
        // TODO: Implement saveInternal() method.
    }

    public function delete($name)
    {
        return parent::deleteDir($name);
    }

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return bool
     */
    public function exists($name)
    {
        return parent::has($name);
    }

    /**
     * Retrieves the url address of file
     * @param $name
     */
    public function url($name)
    {
        return $this->baseUrl . str_replace('\\', '/', $name);
    }
}