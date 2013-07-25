<?php

namespace Frigg\Controllers;

use Frigg\Core as App;
use Frigg\Entity as Entity;
use Frigg\Entity\Repository;

class ProfileController extends BaseController
{
    public function indexAction($request)
    {
        $this->http->redirect('/?profile&action=list');
    }

    public function listAction($request)
    {
        $registry = App\Registry::singleton();
        $em = $registry->getComponent('db')->getEntityManager();

        $collection = $em->getRepository('Frigg\Entity\Profile')
            ->findBy(
                array(),
                array('id' => 'DESC')
            );

        $collection = (!$collection) ? array() : $collection;
        return $this->tpl->render('profile/list.html.twig', array(
            'collection' => $collection
        ));
    }

    public function viewAction($request)
    {
        $registry = App\Registry::singleton();
        $em = $registry->getComponent('db')->getEntityManager();

        if(!isset($request['id']))
        {
             return $this->error('Missing Profile ID', 404);
        }

        $profileId = (int) $request['id'];
        $profileObj = $em->getRepository('Frigg\Entity\Profile')->find($profileId);

        if(!$profileObj)
        {
            return $this->error(sprintf('Unable to find profile %d', $profileId), 404);
        }

        return $this->tpl->render('profile/view.html.twig', array(
            'profile' => $profileObj
        ));
    }

    public function createAction($request)
    {
        $registry = App\Registry::singleton();
        $postVars = $this->http->getPost();

        if(!isset($postVars['submit']))
        {
            return $this->tpl->render('profile/create.html.twig');
        }

        $name = ($postVars['name']) ? (string) $postVars['name'] : '';
        $number = ($postVars['number']) ? (int) $postVars['number'] : 0;
        $amount = ($postVars['amount']) ? (int) $postVars['amount'] : 0;

        $em = $registry->getComponent('db')->getEntityManager();

        try
        {
            $profile = new Entity\Profile;
            $profile->setName($name);
            $profile->setCreated(time());

            $account = new Entity\Account;
            $account->setProfile($profile);
            $account->setNumber($number);
            $account->setAmount($amount);
            
            $em->persist($account);
            $em->flush();
        }
        catch(\Exception $e)
        {
            return $this->error($e->getMessage());
        }

        return $this->tpl->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true
        ));
    }
}
