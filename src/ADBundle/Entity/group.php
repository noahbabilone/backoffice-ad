<?php

namespace ADBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use  Doctrine\Common\Collections\Collection;

/**
 * User
 *
 * @ORM\Table(name="group")
 */
class Group
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    /**
     * @var string
     *
     * @ORM\Column(name="dn", type="string", length=255)
     */
    private $dn;


    private $OU = array("Saint-Mandé", "Luxembourg", "Issy-Les-Moulineaux", "test", "test2", 'test3');

    /**
     * @ORM\ManyToMany(targetEntity="ADBundle\Entity\User",  cascade={"persist"})
     */
    private $member;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->member = new \ArrayCollection();
    }

    public function init($data)
    {
        if (!empty($data)) {
            $this->setTitle($this->getData($data, "sn"));
            $dn = $this->getData($data, "distinguishedname");
            $this->setDn($dn);

        }
    }


    /**
     * Set title
     *
     * @param string $title
     * @return Group
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Set title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set dn
     *
     * @param string $dn
     * @return User
     */
    public function setDn($dn)
    {
        $this->dn = $dn;

        return $this;
    }


    /**
     * Get dn
     *
     * @return string
     */
    public function getDn()
    {
        return $this->dn;
    }

    /**
     * Set dn
     * @param string $group
     * @return User
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }


    /**
     * @param $array
     * @param $attr
     * @return null
     */
    function getData($array, $attr)
    {
        return isset($array[0][$attr][0]) ? $array[0][$attr][0] : null;
    }


    /**
     * Add member
     *
     * @param User $member
     * @return Film
     */
    public function addMember(User $member)
    {
        $this->member[] = $member;

        return $this;
    }

    /**
     * Remove member
     *
     * @param User $member
     */
    public function removeMember(User $member)
    {
        $this->member->removeElement($member);
    }

    /**
     * Get member
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMember()
    {
        return $this->member;
    }

}
