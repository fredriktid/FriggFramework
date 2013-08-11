<?php

namespace Frigg\Helper;

class FileHelper extends BaseHelper
{
    public function getFileName($file, $withExtension = false)
    {
        if(!is_object($file)) {
            $file = new \SplFileObject($file);
        }

        return ($withExtension) ? $file->getFilename() : substr($file->getFilename(), 0, -1 * (strlen($file->getExtension()) + 1));
    }

    public function createDir($path)
    {
        if(is_dir($path)) {
            if(!is_writable($path)) {
                return chmod(0755);
            }
            return true;
        }

        return mkdir($path, 0755);
    }

    public function readCSVFile($file, $delimiter = ',')
    {
        try {
             $fileObject = new \SplFileObject($file);
        } catch(\Exception $e) {
            throw new \Exception(sprintf('CSV: Cannot open file for reading: %s', $file), 0, $e);
        }

        $fileObject->setFlags(\SplFileObject::READ_CSV);
        $fileObject->setCsvControl($delimiter);

        $result = array();
        foreach($fileObject as $i => $data) {
            $result[$i] = $data;
        }
        return $result;
    }

    public function writeCSVFile($file, $data, $delimiter = ',', $enclosure = '"')
    {
        try {
             $fileObject = new \SplFileObject($file, 'w');
        } catch(\Exception $e) {
            throw new \Exception(sprintf('CSV: Cannot open file for writing: %s', $file), 0, $e);
        }

        $logger = $this->registry->getComponent('log')->setFile('csv_write');
        $logger->write(sprintf('--- Begin write to %s ---', $file));

        $count = 0;
        foreach($data as $fields) {
            if(!is_array($fields)) {
                $logger->write(sprintf('Skipped row %d: Not an array', $i++));
                continue;
            }
            if(!$fileObject->fputcsv($fields, $delimiter, $enclosure)) {
                $logger->write(sprintf('Error writing row %d: %s', $i++, implode($delimiter, $fields)));
                continue;
            }
            $count++;
        }
        
        $logger->write(sprintf('Finished. Wrote %d of %d rows to file.', $count, count($data)));
        fclose($fileObject);
        return $this;
    }

}
