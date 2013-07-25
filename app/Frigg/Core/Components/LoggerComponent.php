<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class LoggerComponent extends BaseComponent
{
    private static $file = 'frigg';
    private static $extension = 'log';

    public function setFile($file)
    {
        self::$file = $file;
        return $this;
    }

    public function getFile()
    {
        return self::$file;
    }

    public function write($message)
    {
        if(!$this->createDir(APP_LOG)) {
            throw new \Exception(sprintf('Unable to create log directory: %s', APP_LOG));
        }

        $filePath =  sprintf('%s/%s.%s', APP_LOG, self::$file, self::$extension);
        $message = sprintf('[%s] %s%s', strftime('%F %T'), preg_replace('/\r|\n/', '', $message), PHP_EOL);
        file_put_contents($filePath, $message, FILE_APPEND);
    }

    public function createDir($target)
    {
        if(is_dir($target)) {
            return true;
        }

        return mkdir($target, 0777);
    }
}
