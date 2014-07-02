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
 * @date 25/06/14.06.2014 13:13
 */

namespace Mindy\Storage;


class MimiBoxStorage extends Storage
{
    /**
     * @var string
     */
    public $mimiboxUrl = "http://mimi-box.com";
    /**
     * @var string
     */
    public $apiKey = '';
    /**
     * @var string
     */
    public $username = '';

    protected function openInternal($name, $mode)
    {
        // TODO: Implement openInternal() method.
    }

    protected function saveInternal($name, $content)
    {
        $file = tempnam(sys_get_temp_dir(), 'POST_MIMIBOX_');
        file_put_contents($file, $content);

        $ch = curl_init($this->mimiboxUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $this->apiKey,
            'X-USERNAME: ' . $this->username
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'files' => "@" . $file . ';filename=' . $name
        ]);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function path($name)
    {
        return rtrim($this->mimiboxUrl, '/') . '/' . $this->username . '/get/?filename=' . $name;
    }

    public function delete($name)
    {
        // TODO: Implement delete() method.
    }

    /**
     * @param $name
     * @throws \Mindy\Exception\Exception
     * @return bool
     */
    public function exists($name)
    {
        // TODO: Implement exists() method.
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param $path
     */
    public function dir($path = null)
    {
        // TODO: Implement dir() method.
    }

    /**
     * Retrieves the list of files and directories from storage py path
     * @param $path
     */
    public function mkDir($path)
    {
        // TODO: Implement mkDir() method.
    }
}

