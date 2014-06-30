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
 * @date 25/06/14.06.2014 13:11
 */

namespace Mindy\Storage;


class MD5FileSystemStorage extends FileSystemStorage
{
    public function getValidFileName($name)
    {
        $fileName = pathinfo($name, PATHINFO_FILENAME);
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        return md5(str_replace("." . $ext, "", $fileName)) . "." . $ext;
    }
}

