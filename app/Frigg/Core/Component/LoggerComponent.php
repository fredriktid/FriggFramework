<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\CoreException;

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
        // check write access to log directory
        $fileHelper = $this->registry->getHelper('frigg/file');
        $logPath = $this->registry->getSetting('frigg/path/log');
        if(!$fileHelper->createDir($logPath)) {
            throw new CoreException(sprintf('Unable to create log directory: %s', $logPath));
        }

        // also to log file
        $logFile = sprintf('%s/%s.%s', $logPath, $this->file, $this->extension);
        if(!is_writable($logFile)) {
            throw new CoreException(sprintf('Unable to write to log file: %s', $logFile));
        }

        $httpObject = $this->registry->getComponent('frigg/http');

        // write log to file (removes newlines and carriage returns)
        $message = sprintf('[%s] %s: %s%s', strftime('%F %T'), $httpObject->remoteAddr(), preg_replace('/\r|\n/', '', $message), PHP_EOL);
        file_put_contents($logFile, $message, FILE_APPEND);
        return $this;
    }
}
