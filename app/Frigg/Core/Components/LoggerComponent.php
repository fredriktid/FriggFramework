<?php

namespace Frigg\Core\Components;

defined('APP_TOKEN') or die('This file can not be called directly');

class LoggerComponent extends BaseComponent
{
    private static $file = 'frigg';
    private static $extension = 'log';

    public function setFile($file)
    {
        static::$file = $file;
        return $this;
    }

    public function getFile()
    {
        return static::$file;
    }

    public function write($message)
    {
        if(!$this->createDir(APP_LOG)) {
            throw new \Exception(sprintf('Unable to create log directory: %s', APP_LOG));
        }

        $filePath =  sprintf('%s/%s.%s', APP_LOG, static::$file, static::$extension);
        $message = sprintf('[%s] %s%s', strftime('%F %T'), preg_replace('/\r|\n/', '', $message), PHP_EOL);
        file_put_contents($filePath, $message, FILE_APPEND);
        return $this;
    }

    public function createDir($target)
    {
        if(is_dir($target)) {
            return true;
        }

        return mkdir($target, 0755);
    }
}
