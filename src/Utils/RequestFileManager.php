<?php

namespace App\Utils;

use Exception;


final class RequestFileManager
{
    private function __construct() {}

    public static function mountFile()
    {
        if (!$_FILES)  throw new Exception('file not found', 404);

        if (\count($_FILES) > 1) {
            throw new Exception('file limit exceeded', 404);
        }

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

        $data = [];
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
