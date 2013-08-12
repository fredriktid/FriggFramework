<?php

namespace Frigg\Helper;

class ValidateHelper extends BaseHelper
{
    public function validateForm($formFields, $postData)
    {
    	// required types
    	if(!is_array($formFields) || !is_array($postData)) {
    		return array('Bad data');
    	}

    	// default values
    	$errors = array();
    	$formFields = (!isset($formFields['fields'])) ? array() : $formFields['fields'];

        // validate unique fields
        foreach($formFields as $formAttribute => $formData) {
            if($formData['unique'] && isset($postData[$formAttribute])) {
                $formValue = $postData[$formAttribute];
                $entityManager = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
                if($object = $entityManager->getRepository('Demo\Entity\Account')->findBy(array($formAttribute => $formValue))) {
                    $error[] = sprintf('Account %s already exists', ucfirst($formValue));
                }
            }
        }

        
        foreach($postData as $postAttribute => $postValue) {
            $postAttribute = trim($postAttribute);
            $postValue = trim($postValue);
            if(!array_key_exists($postAttribute, $formFields)) {
                $errors[$postAttribute] = sprintf('Invalid data submitted: %s=%s', $postAttribute, $postValue);
                continue;
            }

            $fieldData = $formFields[$postAttribute];
            if($fieldData['required']) {
                switch($fieldData['type']) {
                    case 'string':
                        if(!strlen($postValue)) {
                            $errors[$postAttribute] = sprintf('%s is required and must have content', ucfirst($postAttribute));
                        }
                        break;
                    case 'integer':
                        $postValue = (int)$postValue;
                        if(!$postValue) {
                            $errors[$postAttribute] = sprintf('%s is required and must be valid integer', ucfirst($postAttribute));
                        }
                        break;
                    case 'array':
                        if(!is_array($postValue)) {
                            $errors[$postAttribute] = sprintf('%s is required and must be an array', ucfirst($postAttribute));
                        }
                        break;
                    case 'float':
                    	$postValue = floatval($postValue);
                        if(!(0 < $postValue)) {
                            $errors[$postAttribute] = sprintf('%s is required and must be floating point number', ucfirst($postAttribute));
                        }
                }
            }
        }
        return $errors;
    }
}
