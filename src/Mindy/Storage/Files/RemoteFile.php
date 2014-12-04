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
 * @date 30/06/14.06.2014 18:53
 */

namespace Mindy\Storage\Files;


class RemoteFile extends File
{
    function urlExists($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if($code == 200){
            $status = true;
        }else{
            $status = false;
        }
        curl_close($ch);
        return $status;
    }

    function getInfo($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        $size = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        $mime = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        return [$size, $mime];
    }

    public function __construct($path)
    {
        if(!$this->urlExists($path)) {
            throw new \Mindy\Exception\Exception("File {$path} not found");
        }

        list($size, $type) = $this->getInfo($path);
        $this->path = $path;
        $this->name = basename($path);
        $this->size = $size;
        $this->type = $type;
    }
}
