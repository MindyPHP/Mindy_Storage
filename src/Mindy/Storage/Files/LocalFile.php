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


class LocalFile extends File
{
    public function __construct($path)
    {
        if(!is_file($path)) {
            throw new \Mindy\Base\Exception\Exception("File {$path} not found");
        }

        $this->path = $path;
        $this->name = basename($path);
        $this->size = filesize($path);

        if (function_exists("finfo_file")) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
            $mime = finfo_file($finfo, $path);
            finfo_close($finfo);
        } else if (function_exists("mime_content_type")) {
            $mime = mime_content_type($path);
        } else {
            throw new \Mindy\Base\Exception\Exception("Unknown file extension");
        }
        $this->type = $mime;
    }
}
