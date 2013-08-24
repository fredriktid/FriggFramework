<?php

namespace Frigg\Helper;

use Frigg\Core\Exception\ErrorException;

class FileHelper extends HelperBase
{
    public static function getFileName($file, $withExtension = false)
    {
        if(!is_object($file)) {
            $file = new \SplFileObject($file);
        }

        return ($withExtension) ? $file->getFilename() : substr($file->getFilename(), 0, -1 * (strlen($file->getExtension()) + 1));
    }

    public static function createDir($path)
    {
        if(is_dir($path)) {
            if(!is_writable($path)) {
                return chmod(0755);
            }
            return true;
        }

        return mkdir($path, 0755);
    }

    public static function readCSVFile($file, $delimiter = ',')
    {
        try {
             $fileObject = new \SplFileObject($file);
        } catch(\RuntimeException $e) {
            throw $e;
        }

        $fileObject->setFlags(\SplFileObject::READ_CSV);
        $fileObject->setCsvControl($delimiter);

        $result = array();
        foreach($fileObject as $i => $data) {
            $result[$i] = $data;
        }

        return $result;
    }

    public static function writeCSVFile($file, $data, $delimiter = ',', $enclosure = '"')
    {
        try {
             $fileObject = new \SplFileObject($file, 'w');
        } catch(\RuntimeException $e) {
            throw $e;
        }

        $count = 0;
        foreach($data as $fields) {
            if(!is_array($fields)) {
                continue;
            }
            if(!$fileObject->fputcsv($fields, $delimiter, $enclosure)) {
                continue;
            }
            $count++;
        }

        return $this;
    }
}
