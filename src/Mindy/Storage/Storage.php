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
 * @date 24/06/14.06.2014 18:48
 */

namespace Mindy\Storage;


use Mindy\Core\Object;
use Mindy\Exception\Exception;

abstract class Storage extends Object
{
    /**
     * Retrieves the specified file from storage.
     * @param $name
     * @param string $mode
     */
    public function open($name, $mode = 'rb')
    {
        return $this->openInternal($name, $mode);
    }

    abstract protected function openInternal($name, $mode);

    public function getValidFileName($name)
    {
        return $name;
    }

    /**
     * Saves new content to the file specified by name. The content should be
     * a proper File object or any python file-like object, ready to be read
     * from the beginning.
     * @param $name
     * @param $content
     * @return mixed
     */
    public function save($name, $content)
    {
        $name = $this->getValidFileName($name);
        $name = $this->getAvailableName($name);
        return $this->saveInternal($name, $content) ? str_replace('\\', '/', $name) : false;
    }

    abstract protected function saveInternal($name, $content);

    /**
     * Returns a filename that's free on the target storage system, and
     * available for new content to be written to.
     * @param $name
     * @return string
     */
    public function getAvailableName($name)
    {
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        $fileName = str_replace("." . $ext, "", $name);

        $count = 0;
        while ($this->exists($name)) {
            $count += 1;
            $name = strtr("{filename}_{count}.{ext}", [
                '{filename}' => $fileName,
                '{count}' => $count,
                '{ext}' => $ext
            ]);
        }
        return $name;
    }

    abstract public function delete($name);

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return bool
     */
    abstract public function exists($name);

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return string
     */
    public function path($name)
    {
        throw new Exception("This backend doesn't support this feature.");
    }
}
