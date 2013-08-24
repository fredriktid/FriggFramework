<?php defined('APP_TOKEN') or die('No direct script access.');

return array(
    'profile' => array(
        'create_profile' => array(
            'fields' => array(
                'name' => array(
                    'name' => 'Name',
                    'type' => 'string',
                    'input' => 'text',
                    'required' => true,
                    'unique' => false
                ),
                'number' => array(
                    'name' => 'Account number',
                    'type' => 'integer',
                    'input' => 'text',
                    'required' => true,
                    'unique' => true
                ),
                'amount' => array(
                    'name' => 'Amount',
                    'type' => 'integer',
                    'input' => 'text',
                    'required' => true,
                    'unique' => false
                ),
                'submit' => array(
                    'name' => 'Create new profile',
                    'type' => 'submit',
                    'input' => 'button',
                    'required' => false,
                    'unique' => false
                )
            )
        )
    )
);
