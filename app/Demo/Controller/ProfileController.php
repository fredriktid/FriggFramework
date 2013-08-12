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
        // necessary component instances
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();

        // parameter 'id' is required
        if(!isset($request['id'])) {
             throw new EntityException('Missing ID');
        }

        // fetch profile from database by reference id
        $profileId = (int) $request['id'];
        $profileObj = $db->getRepository('Demo\Entity\Profile')->find($profileId);
        if(!$profileObj) {
            throw new EntityException(sprintf('Unable to find profile %d', $profileId));
        }

        // if successful, render user profile template
        return $tpl->render('profile/view.html.twig', array(
            'profile' => $profileObj
        ));
    }

    public function createAction($request)
    {
        // necessary component instances
        $http = $this->registry->getComponent('http');
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
        $form = $this->registry->getComponent('config')->getConfig('forms', 'profile'); 

        // if no form has been submitted, render create template
        $postRequest = $http->postVariables();
        if(!isset($postRequest['submit'])) {
            return $tpl->render('profile/create.html.twig', array(
                'errors' => false
            ));
        }
        
        // if errors, halt and display them
        $validator = $this->registry->getHelper('frigg_validate');
        $errors = $validator->validateForm($form['create'], $postRequest);
        if(0 < count($errors)) {
            return $tpl->render('profile/create.html.twig', array(
                'errors' => $errors
            ));
        }

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
                'errors' => array(sprintf('Error saving profile w/account: %s', $e->getMessage()))
            ));
        }

        // if successful, render newly created profile for first time
        return $tpl->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true
        ));
    }
}
