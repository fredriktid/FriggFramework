<?php

namespace Demo\Controllers;

use Frigg\Core as App;
use Frigg\Controllers\BaseController;
use Demo\Entity as Entity;
use Demo\Entity\Repository;

class ProfileController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->log->setFile('profile');
    }

    public function indexAction($request)
    {
        $this->http->redirect('/?profile&action=list');
    }

    public function listAction($request)
    {
        $registry = App\Registry::singleton();
        $em = $registry->getComponent('db')->getEngine('doctrine')->instance;

        $collection = $em->getRepository('Demo\Entity\Profile')
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
        $em = $registry->getComponent('db')->getEngine('doctrine')->instance;

        if (!isset($request['id'])) {
             throw new \Exception('Missing ID');
        }

        $profileId = (int) $request['id'];
        $profileObj = $em->getRepository('Demo\Entity\Profile')->find($profileId);

        if (!$profileObj) {
            throw new \Exception('Unable to find profile');
        }

        return $this->tpl->render('profile/view.html.twig', array(
            'profile' => $profileObj
        ));
    }

    public function createAction($request)
    {
        $registry = App\Registry::singleton();
        $postVars = $this->http->getPost();
        if (!isset($postVars['submit'])) {
            return $this->tpl->render('profile/create.html.twig');
        }

        $name = ($postVars['name']) ? (string) $postVars['name'] : '';
        $number = ($postVars['number']) ? (int) $postVars['number'] : 0;
        $amount = ($postVars['amount']) ? (int) $postVars['amount'] : 0;

        $em = $registry->getComponent('db')->getEngine('doctrine')->instance;
        $accountObj = $em->getRepository('Demo\Entity\Account')->findByNumber($number);
        if ($accountObj) {
            throw new \Exception('Account already exists');
        }

        try {
            $profile = new Entity\Profile;
            $profile->setName($name);
            $profile->setCreated(time());

            $account = new Entity\Account;
            $account->setProfile($profile);
            $account->setNumber($number);
            $account->setAmount($amount);
            
            $em->persist($account);
            $em->flush();
            $this->log->write(sprintf('Created profile %d: %s', $profile->getId(), $profile->getName()));
        } catch(\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return $this->tpl->render('profile/view.html.twig', array(
            'profile' => $profile,
            'firsttime' => true
        ));
    }
}
