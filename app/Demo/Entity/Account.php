<?php

namespace Demo\Entity;

/**
 * @Entity(repositoryClass="Demo\Entity\Repository\AccountRepository")
 */
class Account
{
    /**
     * @Id @Column(type="integer")
     * @GeneratedValue
     **/
    private $id;

    /**
    * @Column(type="integer", unique = true)
    **/
    private $number;

    /**
    * @Column(type="integer")
    **/
    private $amount;

   /**
    * @OneToOne(targetEntity="Profile", mappedBy="account", cascade={"persist"})
    */
    private $profile;

    public function getId()
    {
        return $this->id;
    }

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

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function depositAmount($amount)
    {
        $this->amount += $amount;
    }

    public function withdrawAmount($amount)
    {
        $this->amount -= $amount;
    }
}
