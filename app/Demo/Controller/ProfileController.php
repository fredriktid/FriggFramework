<?php

namespace Demo\Controller;

use Frigg\Core as App;
use Frigg\Controller\BaseController;
use Frigg\Core\Exception\EntityException;
use Demo\Entity as Entity;
use Demo\Entity\Repository;
    
class ProfileController extends BaseController
{
    public function indexAction($request)
    {
        $this->registry->getComponent('http')->redirect('/?profile&action=list');
    }

    public function listAction($request)
    {
        // necessary component instances
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();

        // fetch collection of profiles by newest first
        $collection = $db->getRepository('Demo\Entity\Profile')
            ->findBy(
                array(),
                array('id' => 'DESC')
            );

        // format and render list template
        $collection = (!$collection) ? array() : $collection;
        return $tpl->render('profile/list.html.twig', array(
            'collection' => $collection
        ));
    }

    public function viewAction($request)
    {
        // db engine
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();        

        // a required paramenter
        $errors = array();
        $profileObj = null;
        if(!isset($request['id'])) {
            $errors[] = 'Missing profile ID';
        } else {
            $profileId = (int) $request['id'];
            $profileObj = $db->getRepository('Demo\Entity\Profile')->find($profileId);
            if(!$profileObj) {
                $errors[] = 'Profile does not exist in database with an id reference';
            }
        }

        // if successful, render user profile template
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();
        return $tpl->render('profile/view.html.twig', array(
            'errors' => $errors,
            'profile' => $profileObj,
            'request' => $request
        ));
    }

    public function createAction($request)
    {
        // form settings
        $formSection = $this->registry->getComponent('config')->getConfig('forms', 'profile');
        $formSettings = $formSection['create'];

        // necessary component instances
        $http = $this->registry->getComponent('http');
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();

        // if no form has been submitted, render create template
        $postRequest = $http->postVariables();
        if(!isset($postRequest['submit'])) {
            return $tpl->render('profile/create.html.twig', array(
                'form' => $formSettings,
                'errors' => array(),
                'request' => $request
            ));
        }
        
        // validate form data against config settings
        $validator = $this->registry->getHelper('frigg_validate');
        $errors = $validator->validateForm($formSettings, $postRequest);
        if(0 < count($errors)) {
            return $tpl->render('profile/create.html.twig', array(
                'form' => $formSettings,
                'errors' => $errors,
                'request' => $request
            ));
        }

        // if successful, create a new profile w/account
        // persist both objects to database
        try {
            // set profile data
            $profile = new Entity\Profile;
            $profile->setName($postRequest['name']);
            $profile->setCreated(time());

            // set account data
            $account = new Entity\Account;
            $account->setProfile($profile); // relation
            $account->setNumber($postRequest['number']);
            $account->setAmount($postRequest['amount']);
            
            // persist to database
            $db->persist($account);
            $db->flush();
        } catch(\Exception $e) {
            return $tpl->render('profile/create.html.twig', array(
                'form' => $formSettings,
                'errors' => array(sprintf('Error saving profile w/account: %s', $e->getMessage())),
                'request' => $request
            ));
        }

        // if successful, render newly created profile for first time
        return $tpl->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true,
            'request' => $request
        ));
    }
}
