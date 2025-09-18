<?php

namespace App\Utils;

use Exception;

final class RequestFileManager
{
    private function __construct() {}

    static public function run()
    {
        if (!$_FILES)  throw new Exception('File not found', 404);

        $data = [];

        /** 
         * @var array{
         *  name:string, 
         *  type:string, 
         *  tmp_name:string, 
         *  error:int, 
         *  size:int
         * }
         * $file
         */
        $file = \reset($_FILES);

        $path = $file['tmp_name'];
        $size = \filesize($path);

        if ($size > 2048) throw new Exception('file exceeds 2mb', 413);

        $data['content'] = \file_get_contents($path);
        $data['content_type'] = \finfo_file(
            \finfo_open(FILEINFO_MIME_TYPE),
            $path
        );
        $data['extension'] = \pathinfo($path)['extension'];
        $data['size'] = $size;

        return $data;
    }
}
