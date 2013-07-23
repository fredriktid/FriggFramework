<?php

namespace Frigg\Entity;

/**
 * @Entity(repositoryClass="Frigg\Entity\Repository\AccountRepository")
 */
class Account
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    private $id;

    /**
    * @Column(type="integer")
    **/
    private $number;

   /**
    * @OneToOne(targetEntity="Profile", mappedBy="account", cascade={"persist"})
    */
    private $profile;

    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
        $profile->setAccount($this);
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setNumber($number)
    {
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function getId()
    {
        return $this->id;
    }

    public function deposit($number)
    {
        $this->number += $number;
    }

    public function withdraw($number)
    {
        $this->number -= $number;
    }

}