<?php

namespace Frigg\Core\Component;

class FormComponent extends ComponentBase implements ComponentInterface, FormComponentInterface
{
    public function getSection($identifier)
    {
        return $this->registry->getComponent('frigg/config')->getSection($identifier);
    }

    public function validateForm($formFields, $postData)
    {
        // get doctrines entity manger
        $entityManager = $this->registry->getComponent('frigg/loader')->getInstance('frigg/doctrine');

        // default values
        $errors = array();
        $formFields = (isset($formFields['fields'])) ? $formFields['fields'] : array();

        // validate unique fields
        foreach($formFields as $formIdentifier => $formData) {
            if(isset($formData['unique'])) {
                if($formData['unique'] && isset($postData[$formIdentifier])) {
                    $formValue = $postData[$formIdentifier];
                    if($formIdentifier && $formValue) {
                        if($object = $entityManager->getRepository('Demo\Entity\Account')->findBy(array($formIdentifier => $formValue))) {
                            $errors[] = sprintf('Account %s already exists', ucfirst($formValue));
                        }
                    }
                }
            }
        }

        // validate form data
        foreach($postData as $postIdentifier => $postValue) {
            $postIdentifier = trim($postIdentifier);
            $postValue = trim($postValue);
            if(!array_key_exists($postIdentifier, $formFields)) {
                $errors[$postIdentifier] = sprintf('Invalid data submitted: %s=%s', $postIdentifier, $postValue);
                continue;
            }

            $fieldData = $formFields[$postIdentifier];
            if(isset($fieldData['required']) && $fieldData['required']) {
                $type = (array_key_exists('type', $fieldData) ? strtolower($fieldData['type']) : gettype($postValue));
                switch($type) {
                    case 'string':
                        if(!strlen($postValue)) {
                            $errors[$postIdentifier] = sprintf('%s must have content', ucfirst($postIdentifier));
                        }
                        break;
                    case 'integer':
                        $postValue = (int)$postValue;
                        if(!$postValue) {
                            $errors[$postIdentifier] = sprintf('%s must be valid integer', ucfirst($postIdentifier));
                        }
                        break;
                    case 'array':
                        if(!is_array($postValue)) {
                            $errors[$postIdentifier] = sprintf('%s must be an array', ucfirst($postIdentifier));
                        }
                        break;
                    case 'float':
                        $postValue = floatval($postValue);
                        if(!(0 < $postValue)) {
                            $errors[$postIdentifier] = sprintf('%s must be floating point number', ucfirst($postIdentifier));
                        }
                }
            }
        }
        return $errors;
    }
}
