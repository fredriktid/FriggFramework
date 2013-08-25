<?php

namespace Frigg\Core\Component;

use Frigg\Helper\FileHelper;
use Frigg\Core\Exception\ErrorException;

class LoggerComponent extends ComponentBase implements ComponentInterface, LoggerComponentInterface
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
        $logPath = $this->registry->getSetting('path/log');
        if(!FileHelper::createDir($logPath)) {
            throw new ErrorException(sprintf('Unable to create log directory: %s', $logPath));
        }

        // also to log file
        $logFile = sprintf('%s/%s.%s', $logPath, $this->file, $this->extension);
        if(!is_writable($logFile)) {
            throw new ErrorException(sprintf('Unable to write to log file: %s', $logFile));
        }

        $httpObject = $this->registry->getComponent('frigg/http');

        // write log to file (removes newlines and carriage returns)
        $message = sprintf('[%s] %s: %s%s', strftime('%F %T'), $httpObject->remoteAddr(), $message, PHP_EOL);
        file_put_contents($logFile, $message, FILE_APPEND);
        return $this;
    }
}
