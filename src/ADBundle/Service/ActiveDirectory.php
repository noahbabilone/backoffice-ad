<?phpnamespace ADBundle\Service;use ADBundle\Entity\User;use Symfony\Component\Validator\Constraints\False;/** * Description of AD * * @author Yann */class ActiveDirectory{    protected $host = "636";    protected $hosts = "636";    protected $ipServer;    protected $ldapUser;    protected $ldapPass;    protected $ldapConnect;    protected $ldapBind;    protected $ldapTree;// = "DC=42group,DC=cloud";    protected $smtp = array('42consulting.fr', "42consulting.lu", "42mediatvcom.com", "42mediatvcom.fr");    protected $OU = array("Saint-Mandé", "Luxembourg", "Issy-Les-Moulineaux", "test1", "test2", 'test3', 'ok');    /**     * @param $tree     * @param $ipServer     * @param $ldapUser     * @param $ldapPass     */    public function __construct($tree, $ipServer, $ldapUser, $ldapPass)    {        $this->ldapTree = $tree;        $this->ipServer = $ipServer;        $this->ldapUser = $ldapUser;        $this->ldapPass = $ldapPass;        $this->ldapConnect = @ldap_connect("ldaps://" . $this->ipServer, $this->host);// or die("Could not connect to LDAP server.");        ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);        ldap_set_option($this->ldapConnect, LDAP_OPT_REFERRALS, 0);        if ($this->ldapConnect) {            ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);            ldap_set_option($this->ldapConnect, LDAP_OPT_REFERRALS, 0);            $this->ldapBind = @ldap_bind($this->ldapConnect, $this->ldapUser, $this->ldapPass);//or die ("Error trying to bind: " . ldap_error($this->ldapConnect));        }    }    /**     * @param $ldapUser     * @param $password     * @return bool     */    function checkSession($ldapUser, $password)    {        $result = false;        if ($this->ldapConnect) {            ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);            ldap_set_option($this->ldapConnect, LDAP_OPT_REFERRALS, 0);            $result = @ldap_bind($this->ldapConnect, $ldapUser, $password);        }        return $result;    }    /**     * @return bool|resource     */    public function getLdapConnect()    {        if (!$this->ldapConnect) {            ldap_set_option($this->ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);            ldap_set_option($this->ldapConnect, LDAP_OPT_REFERRALS, 0);            return @ldap_connect("ldaps://" . $this->ipServer, $this->host);// or die("Could not connect to LDAP server.");        }        return $this->ldapConnect;    }    /**     * @param null $OU     * @return array|null     */    function getAllUser($OU = null)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=User)(objectClass=person)(!(objectClass=computer))(!(CN=Users)))";            if ($OU == null) {                $tree = $this->ldapTree;            } else {                $tree = "OU=" . $OU . "," . $this->ldapTree;            }            $result = @ldap_search($this->ldapConnect, $tree, $filter);            if (!$result) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param null $OU     * @return array|null     */    function getAllComputer($OU = null)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=computer))";            if ($OU == null) {                $tree = $this->ldapTree;            } else {                $tree = "OU=" . $OU . "," . $this->ldapTree;            }            $result = @ldap_search($this->ldapConnect, $tree, $filter);            if (!$result) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param $dn     * @return array|null     */    function getByDn($dn)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=*))";            $result = @ldap_search($this->ldapConnect, $dn, $filter);            if (!$result) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }                            /**     * @param null $OU     * @return array|null     */    function getAllGroup($OU = null)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=Group))";            if ($OU == null) {                foreach ($this->OU as $val) {                    $tree = "OU=" . $val . "," . $this->ldapTree;                    $result = @ldap_search($this->ldapConnect, $tree, $filter);                    //or die ("Error in search query: " . ldap_error($this->ldapConnec));                    if ($result !== false) {                        $group = ldap_get_entries($this->ldapConnect, $result);                        if (count($group) > 1) {                            $data[] = $group;                        }                    }                }            } else {                $tree = "OU=" . $OU . "," . $this->ldapTree;                $result = @ldap_search($this->ldapConnect, $tree, $filter);// or die ("Error in search query: " . ldap_error($this->ldapConnec));                if ($result !== false)                    $data = ldap_get_entries($this->ldapConnect, $result);            }        }        return $data;    }    /**     * @param $person     * @return array|null     */    function getUser($person)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=User))";            $result = @ldap_search($this->ldapConnect, $person, $filter);            if (!$result) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param $person     * @return array|null     */    function search($person)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectClass=User))";            $result = @ldap_search($this->ldapConnect, $person, $filter);            if (!$result) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param $login     * @return array|null     */    function getUserByLogin($login)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectCategory=person)(objectClass=User)(sAMAccountName=$login))";            $result = @ldap_search($this->ldapConnect, $this->ldapTree, $filter);            // or die ("Error in search query: " . ldap_error($this->ldapConnec));            if ($result === FALSE) {                return $data;            }            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param $username     * @return array|null     */    function getUserByUserPrincipalName($username)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectCategory=person)(objectClass=User)(userPrincipalName=$username))";            $result = @ldap_search($this->ldapConnect, $this->ldapTree, $filter);            if ($result === FALSE)                return $data;            //or die ("Error in search query: " . ldap_error($this->ldapConnec));            $data = ldap_get_entries($this->ldapConnect, $result);        }        return $data;    }    /**     * @param $username     * @return array|null     */    function checkAccessAdmin($username)    {        $data = null;        if ($this->ldapConnect && $this->ldapBind) {            $filter = "(&(objectCategory=person)(objectClass=User)(userPrincipalName=$username))";            $result = @ldap_search($this->ldapConnect, $this->ldapTree, $filter);            if ($result === FALSE)                return FALSE;            if (isset($data[0]["admincount"][0])) {                return ($data[0]["admincount"][0] == 1) ? TRUE : FALSE;            }        }        return FALSE;    }    /**     * @param $ldap_tree     * @return bool     */    function removeUser($ldap_tree)    {        $result = false;        if ($this->ldapConnect && $this->ldapBind) {            $result = @ldap_delete($this->ldapConnect, $ldap_tree);            ldap_close($this->ldapConnect);        }        return $result;    }    /**     * @param $dnGroup     * @param $dnUser     * @return bool     */    function removeUserGroup($dnGroup, $dnUser)    {        $result = false;        if ($this->ldapConnect && $this->ldapBind) {            $group_info['member'] = $dnUser;            $result = @ldap_mod_del($this->ldapConnect, $dnGroup, $group_info);            ldap_close($this->ldapConnect);        }        return $result;    }    /**     * @param User $user     * @return bool     */    function addUser(User $user)    {        if ($this->ldapConnect && $this->ldapBind) {            $info["userprincipalname"][0] = strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();            $index = 0;            foreach ($this->smtp as $at) {                if ($at != $user->getAt()) {                    $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $at;                } else {                    $info["proxyaddresses"][$index] = "SMTP:" . strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();                }                $index++;                $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()[0]) . "." . strtolower($user->getName()) . "@" . $at;                $index++;                $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()[0]) . strtolower($user->getName()) . "@" . $at;                $index++;            }            $info["cn"][0] = $user->getFirstName() . " " . strtoupper($user->getName());            $info["sn"][0] = ucfirst($user->getName()); //Nom            $info["givenname"][0] = ucfirst($user->getFirstName());            $info["displayname"][0] = $user->getFirstName() . " " . $user->getName();            if (!empty($user->getDescription())) {                $info["description"][0] = $user->getDescription();            }            if (!empty($user->getName()))                $info["name"][0] = $user->getName();            if (!empty($user->getPhone()))                $info["telephonenumber"][0] = $user->getPhone(); //Numéro de téléphone            if (!empty($user->getAddress()))                $info["st"][0] = $user->getAddress();            //if (!empty($user->getAddress()))            $info["department"][0] = "Ile-De-France";            if (!empty($user->getCountry()))                $info["c"][0] = "FR"; //Ville            if (!empty($user->getCity()))                $info["l"][0] = $user->getCity(); //Ville            if (!empty($user->getPostalCode()))                $info["postalcode"][0] = $user->getPostalCode(); //Code postal            $info["mail"][0] = strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();            $info["instancetype"][0] = "0";            $info["objectclass"] = array("top", "person", "organizationalPerson", "user");            $info["company"][0] = "42Consulting";            $info["samaccountname"][0] = strtoupper($user->getFirstName()[0]) . strtoupper($user->getName()); //Prénom            $newPassword = "\"" . $user->getPassword() . "\"";            $newPassw = "";            $len = strlen($newPassword);            for ($i = 0; $i < $len; $i++)                $newPassw .= "{$newPassword{$i}}\000";            $newPassword = $newPassw;            $info["unicodepwd"] = $newPassword;            $info["useraccountcontrol"][0] = 544; //Activation du compte            $ldap_tree = "CN=" . $info["cn"][0] . ",OU=" . $user->getService() . "," . $this->ldapTree;            if ($this->personExist($info["cn"][0])) {                return "L'utlisateur existe déjà";            }            $result = ldap_add($this->ldapConnect, $ldap_tree, $info);            if ($result && $user->getGroup() !== null) {                $groups = explode('#DnGroup:', $user->getGroup());                foreach ($groups as $group) {                    if (!empty($group)) {                        $group_name = $group . "," . $this->ldapTree;                        $group_info['member'] = $ldap_tree; // User's DN is added to group's 'member' array                        $result = @ldap_mod_add($this->ldapConnect, $group_name, $group_info);                    }                }            }            ldap_unbind($this->ldapConnect);            return $result ? "success" : "Error lors de l'ajout";        }        return "Erreur: Ldap";    }    /**     * @param User $user     * @param $dn     * @return bool     */    function editUser(User $user, $dn)    {        if ($this->ldapConnect && $this->ldapBind) {            $result = false;            $info = array();            $data = $this->getUser($dn);            $oldUser = new User();            $oldUser = $oldUser->init($data);            if ($oldUser->getName() != $user->getName()) {                $info["sn"][0] = ucfirst($user->getName()); //Nom            }            if ($oldUser->getFirstName() != $user->getFirstName()) {                $info["givenname"][0] = ucfirst($user->getFirstName());            }            if (!empty($user->getDescription()) && $oldUser->getDescription() != $user->getDescription()) {                $info["description"][0] = $user->getDescription();            }            if (!empty($user->getPhone()) && $oldUser->getPhone() != $user->getPhone()) {                $info["telephonenumber"][0] = $user->getPhone(); //Numéro de téléphone            }            if (!empty($user->getAddress()) && $oldUser->getAddress() != $user->getAddress()) {                $info["st"][0] = $user->getAddress();                $info["department"][0] = "Ile-De-France";            }            if (!empty($user->getCountry()) && $oldUser->getCountry() != $user->getCountry()) {                $info["c"][0] = "FR"; //Ville            }            if (!empty($user->getCity()) && $oldUser->getCity() != $user->getCity()) {                $info["l"][0] = $user->getCity(); //Ville            }            if (!empty($user->getPostalCode()) && $oldUser->getPostalCode() != $user->getPostalCode())                $info["postalcode"][0] = $user->getPostalCode(); //Code postal            if (!empty($user->getPassword())) {                $info["company"][0] = "42Consulting";                $newPassword = "\"" . $user->getPassword() . "\"";                $newPassw = "";                $len = strlen($newPassword);                for ($i = 0; $i < $len; $i++)                    $newPassw .= "{$newPassword{$i}}\000";                $newPassword = $newPassw;                $info["unicodepwd"] = $newPassword;            }            //Désactivé ce compte            //$info["useraccountcontrol"][0] = 544; //Activation du compte            $userPrincipalName = strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();            if ($oldUser->getLogin() != $userPrincipalName) {                $info["mail"][0] = $userPrincipalName;                $index = 0;            }            if ($user->getAt() != $oldUser->getAt()) {                foreach ($this->smtp as $at) {                    if ($at != $user->getAt()) {                        $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $at;                    } else {                        $info["proxyaddresses"][$index] = "SMTP:" . strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();                    }                    $index++;                    $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()[0]) . "." . strtolower($user->getName()) . "@" . $at;                    $index++;                    $info["proxyaddresses"][$index] = "smtp:" . strtolower($user->getFirstName()[0]) . strtolower($user->getName()) . "@" . $at;                    $index++;                }            }            if ($oldUser->getFirstName() != $user->getFirstName() || $oldUser->getName() != $user->getName()) {                $info["displayname"][0] = $user->getFirstName() . " " . $user->getName();                $info["samaccountname"][0] = strtoupper($user->getFirstName()[0]) . strtoupper($user->getName()); //Prénom            }            if ($oldUser->getFirstName() != $user->getFirstName() || $oldUser->getName() != $user->getName() || $user->getAt() != $oldUser->getAt()) {                $info["userprincipalname"][0] = strtolower($user->getFirstName()) . "." . strtolower($user->getName()) . "@" . $user->getAt();            }            //MAJ Dn            $ldap_tree = "CN=" . $user->getFirstName() . " " . strtoupper($user->getName()) . ",OU=" . $user->getService() . "," . $this->ldapTree;            if ($oldUser->getFirstName() != $user->getFirstName() || $oldUser->getName() != $user->getName() || $user->getService() != $oldUser->getService() || $ldap_tree != $oldUser->getDn()) {                $dnRoot = "OU=" . $user->getService() . " ," . $this->ldapTree;                $newDn = "CN=" . $user->getFirstName() . " " . strtoupper($user->getName());                $result = @ldap_rename($this->ldapConnect, $user->getDn(), $newDn, $dnRoot, TRUE);                $user->setDn($ldap_tree);            }            //MAJ les informations             if (!empty($info)) {                $result = ldap_mod_replace($this->ldapConnect, $user->getDn(), $info);            }            //add groupes            if ($user->getGroup() !== null) {                $groups = explode('#DnGroup:', $user->getGroup());                foreach ($groups as $group) {                    if (!empty($group)) {                        $group_name = $group . "," . $this->ldapTree;                        if (!($this->checkGroup($user->getDn(), $group_name))) {                            echo "Add";                            $group_info['member'] = $ldap_tree; // User's DN is added to group's 'member' array                            $result = @ldap_mod_add($this->ldapConnect, $group_name, $group_info);                        }                    }                }            }            if ($user->getGroupNotSelect() !== null) {                $groups = explode('#DnGroup:', $user->getGroupNotSelect());                foreach ($groups as $group) {                    if (!empty($group)) {                        $group_name = $group . "," . $this->ldapTree;                        if ($this->checkGroup($user->getDn(), $group_name)) {                            $group_info['member'] = $user->getDn();                            $result = @ldap_mod_del($this->ldapConnect, $group_name, $group_info);                        }                    }                }            }            if ($result) {                ldap_close($this->ldapConnect);                return $user;            }        }        return null;    }    /**     * @param User $user     * @return string     */    function changePasswordUser(User $user)    {        if ($this->checkSession($user->getLogin(), $user->getOldPassword())) {            putenv('LDAPTLS_REQCERT=never');            $ldapConnect = ldap_connect('ldaps://' . $this->ipServer . ':636') or die("Could not connect to LDAP server.");            ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);            ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);            if ($ldapConnect) {                $ldapBind = @ldap_bind($ldapConnect, $this->ldapUser, $this->ldapPass);                if ($ldapBind) {                    $newPassword = "\"" . $user->getPassword() . "\"";                    $newPassw = "";                    $len = strlen($newPassword);                    for ($i = 0; $i < $len; $i++)                        $newPassw .= "{$newPassword{$i}}\000";                    $newPassword = $newPassw;                    $userdata["unicodepwd"] = $newPassword;                    $result = ldap_mod_replace($ldapConnect, $user->getDn(), $userdata);                    ldap_close($ldapConnect);                    if ($result)                        return "success";                    else                        return "Erreur s'est produit lors de la modification : " . ldap_error($this->ldapConnect);                } else {                    return "Erreur lors de l'exécution de la fonction ldap_bind(): " . ldap_error($this->ldapConnect);                }            } else {                return "Erreur: Pas de Connexion LDAP Server";            }        } else {            return "Ancien mot de passe incorrect";        }    }    /**     *     */    function testLdaps()    {        if ($this->checkSession($this->ldapUser, $this->ldapPass)) {            putenv('LDAPTLS_REQCERT=never');            $ldapConnect = ldap_connect('ldaps://' . $this->ipServer . ':636') or die("Could not connect to LDAP server.");            ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);            ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);            if ($ldapConnect) {                $ldapBind = @ldap_bind($ldapConnect, $this->ldapUser, $this->ldapPass);                if ($ldapBind) {                    dump($ldapBind);                    die("OKKKKKK");                }                die("Erreur ldap_bind(): ");            }            die("Erreur ldap_connect(): " . ldap_error($this->ldapConnect));        }        die('No Connect');    }    /**     * @param $user     * @return bool     */    function personExist($user)    {        if ($this->ldapConnect && $this->ldapBind) {            if (@ldap_search($this->ldapConnect, "CN=" . $user . "," . $this->ldapTree, "(cn=*)")) {                return true;            }        }        return false;    }    /* Functions */    /**     * @param $array     * @param $attr     * @return string     */    function getData($array, $attr)    {        return isset($array[$attr][0]) ? $array[$attr][0] : " ";    }    /* Functions */    /**     * @param $array     * @param $attr     * @return string     */    function getArray($array, $attr)    {        return isset($array[$attr]) ? $array[$attr] : Null;    }    /**     * @param $array     * @param $attr     * @return null     */    function getOneData($array, $attr)    {        return isset($array[0][$attr][0]) ? $array[0][$attr][0] : null;    }    /**     * @param $data     * @return string     */    function base64Encode($data)    {        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');    }    /**     * @param $data     * @return string     */    function base64Decode($data)    {        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));    }    function checkMemberOfGroup($data, $dnUser)    {        return (isset($data["member"])) ? in_array($dnUser, $data["member"]) : false;    }    /** This function checks group membership of the user, searching only* in specified group (not recursively).*/    function checkGroup($userDn, $groupDn)    {        if ($this->ldapConnect && $this->ldapBind) {            $attributes = array('members');            $result = @ldap_read($this->ldapConnect, $userDn, "(memberof={$groupDn})", $attributes);            if ($result === FALSE) {                return FALSE;            }            $entries = ldap_get_entries($this->ldapConnect, $result);            return ($entries['count'] > 0);        }    }}