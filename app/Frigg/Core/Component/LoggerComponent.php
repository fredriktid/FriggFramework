<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\ComponentException;

defined('APP_TOKEN') or die('This file can not be called directly');

class LoggerComponent extends BaseComponent
{
    protected $file = 'default';
    protected $extension = 'log';

    public function setFile($file)
    {
        $this->file = $file;
        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function write($message)
    {
        // validate log directory
        $fileHelper = $this->registry->getHelper('frigg_file');
        $logPath = $this->registry->getSetting('frigg_path_log');
        if(!$fileHelper->createDir($logPath)) {
            throw new ComponentException(sprintf('Unable to create log directory: %s', $logPath));
        }

        // validate log file
        $logFile = sprintf('%s/%s.%s', $logPath, $this->file, $this->extension);
        if(!is_writable($logFile)) {
            throw new ComponentException(sprintf('Unable to write to log file: %s', $logFile));
        }

        // get http componenet
        $httpComponent = $this->registry->getComponent('http');

        // write log to file (removes newlines and carriage returns)
        $message = sprintf('[%s] %s: %s%s', strftime('%F %T'), $httpComponent->remoteAddr(), preg_replace('/\r|\n/', '', $message), PHP_EOL);
        file_put_contents($logFile, $message, FILE_APPEND);
        return $this;
    }
}
