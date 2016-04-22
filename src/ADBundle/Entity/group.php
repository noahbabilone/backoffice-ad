<?php

namespace ADBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
    
    private $OU = array("Saint-Mandé", "Luxembourg", "Issy-Les-Moulineaux", "test", "test2", 'test3');


    public function init($data)
    {
        if (!empty($data)) {

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



}
