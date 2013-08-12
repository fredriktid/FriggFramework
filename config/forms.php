<?php defined('APP_TOKEN') or die('No direct script access.');
 
return array(
    'profile' => array(
        'create' => array(
        	'access' => array('all'), // todo
        	'fields' => array(
        		'name' => array(
                    'name' => 'Name',
                    'type' => 'string',
                    'input' => 'text',
                    'entity' => 'Demo\Entity\Profile',
        			'required' => true,
        			'unique' => false,
                    'persist' => true,
        		),
        		'number' => array(
                    'name' => 'Account number',
                    'type' => 'integer',
                    'input' => 'text',
                    'entity' => 'Demo\Entity\Account',
        			'required' => true,
        			'unique' => true,
                    'persist' => true
        		),
        		'amount' => array(
                    'name' => 'Amount',
                    'type' => 'integer',
                    'input' => 'text',
                    'entity' => 'Demo\Entity\Account',
        			'required' => true,
        			'unique' => false,
                    'persist' => true
        		),
        		'submit' => array(
        			'name' => 'Create new profile',
                    'type' => 'submit',
                    'input' => 'button',
                    'entity' => false,
        			'required' => false,
        			'unique' => false,
                    'persist' => false
        		)
        	)
   		)
	)
);
