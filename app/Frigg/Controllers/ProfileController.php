<?php

namespace Frigg\Controllers;

use Frigg\Core as App;
use Frigg\Entity as Entity;
use Frigg\Entity\Repository;

class ProfileController extends BaseController
{
    public function indexAction($request)
    {
        $registry = App\Registry::singleton();
        $em = $registry->getComponent('db')->getEntityManager();

        if(!isset($request['id']))
        {
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

        $profileId = (int) $request['id'];
        $profileObj = $em->getRepository('Frigg\Entity\Profile')->find($profileId);

        if(!$profileObj)
        {
            return $this->error(sprintf('Unable to find profile %d', $profileId), 404);
        }

        return $this->tpl->render('profile/profile.html.twig', array(
            'profile' => $profileObj
        ));
    }

    public function createAction($request)
    {
        $registry = App\Registry::singleton();
        $em = $registry->getComponent('db')->getEntityManager();

        $account = new Entity\Account;
        $profile = new Entity\Profile;

        $profile->setName('Placeholder');
        $account->setProfile($profile);
        $account->setNumber(mt_rand(100000,10000000));
        $em->persist($account);
        $em->flush();

        echo "created";
        echo "<pre>";
        print_r($account);
        print_r($account->getProfile());
        echo "</pre>"; die;
    }
}
