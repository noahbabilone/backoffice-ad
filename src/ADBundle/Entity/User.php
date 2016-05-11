<?php

namespace ADBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="ADBundle\Repository\UserRepository")
 */
class User
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="fullName", type="string", length=255)
     */
    private $fullName;


    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;
    /**
     * @var string
     *
     * @ORM\Column(name="oldPassword", type="string", length=255)
     */
    private $oldPassword;
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     */
    private $address;
    /**  /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;
    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;
    /**
     * @var string
     *
     * @ORM\Column(name="postalCode", type="string", length=255)
     */
    private $postalCode;
    /**
     * @var string
     *
     * @ORM\Column(name="office", type="string", length=255)
     */
    private $office;
    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=255)
     */
    private $service;
    /**
     * @var string
     *
     * @ORM\Column(name="group", type="string", length=255)
     */
    private $group;
    /**
     * @var string
     *
     * @ORM\Column(name="groupNotSelect", type="string", length=255)
     */
    private $groupNotSelect;
    /**
     * @var string
     *
     */
    private $access;


    private $memberOf;

    /**
     * @var string
     *
     * @ORM\Column(name="dn", type="string", length=255)
     */
    private $dn;
    
    /**
     * @var text
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;
    
    /**
     * @var text
     *
     * @ORM\Column(name="at", type="string", length=255)
     */
    private $at;

    private $OU = array("Saint-Mandé", "Luxembourg", "Issy-Les-Moulineaux", "test1", "test2", 'test3');

    public function __construct()
    {
        $this->memberOf = new ArrayCollection();
    }

    public function init($data)
    {
        if (!empty($data)) {
            $this->setName($this->getData($data, "sn"));
            $this->setTitle($this->getData($data, "title"));
            $this->setFirstName($this->getData($data, "givenname"));
            $this->setFullName($this->getData($data, "displayname"));
//        $this->setOffice($this->getData($data, "physicaldeliveryofficename"));
            $this->setAddress($this->getData($data, "st"));
            $this->setCity($this->getData($data, "l"));
            $this->setPostalCode($this->getData($data, "postalcode"));
            $country = $this->getData($data, "c");
            if ($country == "FR") {
                $country = "France";
            } elseif ($country == "LU") {
                $country = "Luxembourg";
            }
            $this->setCountry($country);
            $this->setDescription($this->getData($data, "description"));

            $telephone = $this->getData($data, "telephonenumber");
            if ($telephone !== null) {
                $tel = explode("(0)", $telephone);
                if (count($tel) > 1) {
                    $arrayPhone = str_split($tel[1]);
                    $phone = "0";
                    foreach ($arrayPhone as $key => $number) {
                        if ($number != " ") {
                            $phone .= $number;
                        }
                    }
                    if (count(str_split($phone)) == 10)
                        $this->setPhone($phone);
                }
            }

            $this->setAccess($this->getData($data, "admincount"));

            if (isset($data[0]["memberof"]) && !empty($data[0]["memberof"])) {
                foreach ($data[0]["memberof"] as $key => $val) {
                    if ($key !== "count") {
                        $this->addMemberOf($val);
                    }
                }

            }

            $login = $this->getData($data, "userprincipalname");
            $at = explode("@", $login);
            $this->setLogin($login);
            if (count($at) > 0) {
                $this->setAt(substr(strstr($login, '@', false), 1));
            }
            if (!empty($this->getName()) && !empty($this->getFirstName())) {
                $this->setEmail($this->getFirstName() . "." . $this->getName());
            }
            $dn = $this->getData($data, "distinguishedname");
            $this->setDn($dn);
            $this->setService($this->service($dn));
        }
        return $this;

    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set firstName
     *
     * @param string $firstName
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = ucfirst(strtolower($firstName));

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     * @return User
     */
    public function setFullName($fullName)
    {
        $this->fullName = ucfirst($fullName);

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set oldPassword
     * @param string $oldPassword
     * @return User
     */
    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * Get oldPassword
     *
     * @return string
     */
    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * Set country
     *
     * @param string $country
     * @return User
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set postalCode
     *
     * @param string $postalCode
     * @return User
     */
    public function setPostalCode($postalCode)
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    /**
     * Get postalCode
     *
     * @return string
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }

    /**
     * Set office
     *
     * @param string $office
     * @return User
     */
    public function setOffice($office)
    {
        $this->office = $office;

        return $this;
    }

    /**
     * Get office
     *
     * @return string
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set service
     *
     * @param string $service
     * @return User
     */
    public function setService($service)
    {

        $this->service = $service;
        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return User
     */
    public function setName($name)
    {
        $this->name = ucfirst(strtolower($name));

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return User
     */
    public function setTitle($title)
    {
        $this->title = ucfirst(strtolower($title));

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Set description
     *
     * @param string $description
     * @return User
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get at
     *
     * @return string
     */
    public function getAt()
    {
        return $this->at;
    }

    /**
     * Set at
     *
     * @param string $at
     * @return User
     */
    public function setAt($at)
    {
        $this->at = $at;

        return $this;
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
     * Get access
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set dn
     * @param string $access
     * @return User
     */
    public function setAccess($access)
    {
        $this->access = $access;
        return $this;
    }

    /**
     * Get group
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set groupNotSelect
     * @param string $groupNotSelect
     * @return User
     */
    public function setGroupNotSelect($groupNotSelect)
    {
        $this->groupNotSelect = $groupNotSelect;

        return $this;
    }


    /**
     * Get groupNotSelect
     * @return string
     */
    public function getGroupNotSelect()
    {
        return $this->groupNotSelect;
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

    function service($dn)
    {
        $result = null;
        foreach ($this->OU as $ou) {
            if (strpos(strtolower($dn), strtolower("OU=" . $ou)) !== false) {
                return strtolower($ou);
            }
        }
        return $result;
    }


    /**
     * Add member
     *
     * @param $member
     * @return $this
     */
    public function addMemberOf($member)
    {
        $this->memberOf[] = $member;
        return $this;
    }

    /**
     * Remove member
     * @param $member
     */
    public function removeMemberOf($member)
    {
        $this->memberOf->removeElement($member);
    }

    /**
     * Get member
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMember()
    {
        return $this->memberOf;
    }

    public static function checkAdminUser($data)
    {
        if (isset($data[0]["admincount"][0])) {
            return ($data[0]["admincount"][0] == 1) ? TRUE : FALSE;
        }
        return FALSE;

    }
}
