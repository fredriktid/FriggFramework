<?php

namespace Demo\Controller;

use Frigg\Core as App;
use Frigg\Controller\BaseController;
use Frigg\Core\Exception\ControllerException;
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
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();

        $collection = $db->getRepository('Demo\Entity\Profile')
            ->findBy(
                array(),
                array('id' => 'DESC')
            );

        $collection = (!$collection) ? array() : $collection;

        return $tpl->render('profile/list.html.twig', array(
            'collection' => $collection
        ));
    }

    public function viewAction($request)
    {
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();

        if(!isset($request['id'])) {
             throw new ControllerException('Missing ID');
        }

        $profileId = (int) $request['id'];
        $profileObj = $db->getRepository('Demo\Entity\Profile')->find($profileId);

        if(!$profileObj) {
            throw new ControllerException(sprintf('Unable to find profile %d', $profileId));
        }

        return $tpl->render('profile/view.html.twig', array(
            'profile' => $profileObj
        ));
    }

    public function createAction($request)
    {
        $http = $this->registry->getComponent('http');
        $tpl = $this->registry->getComponent('engine')->getEngine('twig')->getInstance();
        $db = $this->registry->getComponent('engine')->getEngine('doctrine')->getInstance();

        $postVars = $http->postVariables();
        if(!isset($postVars['submit'])) {
            return $tpl->render('profile/create.html.twig');
        }

        $name = ($postVars['name']) ? (string) $postVars['name'] : '';
        $number = ($postVars['number']) ? (int) $postVars['number'] : 0;
        $amount = ($postVars['amount']) ? (int) $postVars['amount'] : 0;

        $accountObj = $db->getRepository('Demo\Entity\Account')->findByNumber($number);
        if($accountObj) {
            throw new ControllerException('Account already exists');
        }

        try {
            $profile = new Entity\Profile;
            $profile->setName($name);
            $profile->setCreated(time());

            $account = new Entity\Account;
            $account->setProfile($profile);
            $account->setNumber($number);
            $account->setAmount($amount);
            
            $db->persist($account);
            $db->flush();
        } catch (\Exception $e) {
            throw new ControllerException($e->getMessage());
        }

        return $tpl->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true
        ));
    }
}
