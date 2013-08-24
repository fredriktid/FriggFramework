<?php

namespace Frigg\Core\Component;

interface FormComponentInterface
{
    public function getSection($identifier);

    public function validateForm($formFields, $postData);

    // work in progress...
}
