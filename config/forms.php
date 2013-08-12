<?php defined('APP_TOKEN') or die('No direct script access.');
 
return array(
    'profile' => array(
        'create' => array(
        	'access' => 'all',
        	'fields' => array(
        		'name' => array(
        			'type' => 'string',
        			'required' => true,
        			'unique' => false,
        			'name' => 'Name',
        			'persist' => true
        		),
        		'number' => array(
        			'type' => 'integer',
        			'required' => true,
        			'unique' => true,
        			'name' => 'Account number',
        			'persist' => true
        		),
        		'amount' => array(
        			'type' => 'float',
        			'required' => true,
        			'unique' => false,
        			'name' => 'Amount',
        			'persist' => true
        		),
        		'submit' => array(
        			'type' => 'submit',
        			'required' => false,
        			'unique' => false,
        			'name' => 'Create new profile',
        			'persist' => false
        		)
        	)
   		)
	)
);
