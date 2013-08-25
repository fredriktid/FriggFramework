<?php

namespace Frigg\Core\Component;

use Frigg\Core\Exception\ExceptionInterface;

class ErrorComponent extends ComponentBase implements ComponentInterface, ErrorComponentInterface
{
    protected $errors = array();

    public function __toString()
    {
        return sprintf('%s<br>', str_replace(PHP_EOL, '<br>', implode(PHP_EOL, $this->errors)));
    }

    public function addError($message)
    {
        $this->errors[] = $message;
        return $this;
    }

    public function exceptionString(ExceptionInterface $e)
    {
        switch(get_class($e)) {
            case 'Frigg\Core\Exception\RouterException':
                return sprintf('Router said "%s"', $e->getMessage());
            default:
                return sprintf('%s: %s%s%s', get_class($e), $e->getMessage(), PHP_EOL, $e->getTraceAsString());
        }
    }

    public function exceptionHandler(ExceptionInterface $e)
    {
        // add error message
        $message = $this->exceptionString($e);
        $this->addError($message);

        // log critical errors
        switch(get_class($e)) {
            case 'Frigg\Core\Exception\CoreException':
            case 'Frigg\Core\Exception\ErrorException':
                try {
                    $this->registry
                        ->getComponent('frigg/logger')
                        ->setFile('error')
                        ->write($message);
                } catch(\Exception $x) {
                    $message = $this->exceptionString($x);
                    $this->addError($message);
                }
                break;
        }

        return $this;
    }
}
