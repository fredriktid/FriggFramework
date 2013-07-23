<?php

namespace Frigg\Entity;

/**
 * @Entity(repositoryClass="Frigg\Entity\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    private $id;

    /**
    * @Column(length=200)
    **/
    private $name;

    /**
    * @OneToOne(targetEntity="Account", inversedBy="profile", cascade={"persist"})
    */
    private $account;

    public function setAccount(Account $account)
    {
        $this->account = $account; 
    }

    public function getAccount()
    {
        return $this->account;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}