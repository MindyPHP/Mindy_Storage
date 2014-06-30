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


class UploadedFile extends File
{
    public function __construct(array $data)
    {
        $this->name = basename($data['name']);
        $this->path = $data['name'];
        $this->size = $data['size'];
        $this->type = $data['type'];
        $this->error = $data['error'];
    }
}
