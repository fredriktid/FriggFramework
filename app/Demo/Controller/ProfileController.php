<?php

namespace Demo\Controller;

use Frigg\Controller\ControllerBase;
use Demo\Entity as Entity;
use Demo\Entity\Repository;

class ProfileController extends ControllerBase
{
    public function indexAction($request)
    {
        $this->registry->getComponent('frigg/http')->redirect('/?profile&action=list');
    }

    public function listAction($request)
    {
        $template = $this->registry->getComponent('frigg/loader')->getInstance('frigg/twig');
        $entityManager = $this->registry->getComponent('frigg/loader')->getInstance('frigg/doctrine');

        $collection = $entityManager
            ->getRepository('Demo\Entity\Profile')
            ->findBy(
                array(),
                array('id' => 'DESC')
            );

        $collection = (!$collection) ? array() : $collection;
        return $template->render('profile/list.html.twig', array(
            'collection' => $collection,
            'request' => $request
        ));
    }

    public function viewAction($request)
    {
        // get doctrine manager
        $entityManager = $this->registry->getComponent('frigg/loader')->getInstance('frigg/doctrine');

        // validate request data
        $errors = array();
        $profileObj = null;
        if(!isset($request['id'])) {
            $errors[] = 'Missing profile ID';
        } else {
            $profileId = (int) $request['id'];
            $profileObj = $entityManager->getRepository('Demo\Entity\Profile')->find($profileId);
            if(!$profileObj) {
                $errors[] = 'Profile does not exist in database with an id reference';
            }
        }

        // if successful, render user profile template
        $template = $this->registry->getComponent('frigg/loader')->getInstance('frigg/twig');
        return $template->render('profile/view.html.twig', array(
            'errors' => $errors,
            'profile' => $profileObj,
            'request' => $request
        ));
    }

    public function createAction($request)
    {
        $form = $this->registry->getComponent('frigg/form');
        $formData = $form->getSection('forms/profile/create_profile');

        $http = $this->registry->getComponent('frigg/http');
        $template = $this->registry->getComponent('frigg/loader')->getInstance('frigg/twig');
        $entityManager = $this->registry->getComponent('frigg/loader')->getInstance('frigg/doctrine');


        // if no form has been submitted, just render the form template
        $postRequest = $http->postVariables();
        if(!isset($postRequest['submit'])) {
            return $template->render('profile/create.html.twig', array(
                'form' => $formData,
                'errors' => array()
            ));
        }

        // validate form data against config settings
        $errors = $form->validateForm($formData, $postRequest);
        if(0 !== count($errors)) {
            return $template->render('profile/create.html.twig', array(
                'form' => $formData,
                'errors' => $errors
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
            $entityManager->persist($account);
            $entityManager->flush();
        } catch(\Exception $e) {
            return $template->render('profile/create.html.twig', array(
                'form' => $formData,
                'errors' => array(sprintf('Error saving profile w/account: %s', $e->getMessage()))
            ));
        }

        return $template->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true
        ));
    }
}
