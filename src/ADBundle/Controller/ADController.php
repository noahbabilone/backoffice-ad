<?php

namespace ADBundle\Controller;

use ADBundle\Entity\Group;
use ADBundle\Entity\User;
use ADBundle\Form\UserSessionType;
use ADBundle\Form\UserType;
use ADBundle\Form\UserEditType;
use ADBundle\Form\UserEditPasswordType;
use ADBundle\Form\UserEditInfoType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class ADController extends Controller
{
    /**
     * @Route("/dashboard",name="dashboard")
     * @return RedirectResponse|Response
     */
    function indexAction()
    {

        $ad = $this->get("ad_active_directory");

        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('edit_password', array('person' => $this->get('session')->get('user')), 301);
        } /*else {
            return $this->redirectToRoute('logout', array(), 301);
        }*/

        $ad = $this->get("ad_active_directory");
        $users = $ad->getAllUser();
        $usersLocked = $ad->getUserInfoComputer("locked");
        $usersDisabled = $ad->getUserInfoComputer("disabled");
        $neverExpires = $ad->getUserInfoComputer("expires");
        $computers = $ad->getAllComputer();

        $adGroups = $ad->getAllGroup();

        return $this->render('ADBundle:AD:index.html.twig', array(
            "users" => $users,
            "groups" => $adGroups,
            "usersLocked" => $usersLocked,
            "usersDisabled" => $usersDisabled,
            "neverExpires" => $neverExpires,
            "computers" => $computers,
        ));
    }

    /**
     * @Route("/",name="login")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function loginAction(Request $request)
    {
        $ad = $this->get("ad_active_directory");
        $session = $this->get('session');
        if ($session->has('user')) {
            if ($ad->checkAccessAdmin($ad->base64Decode($session->get('user'))) !== FALSE) {
                return $this->redirectToRoute('dashboard', array(), 301);
            }
            return $this->redirectToRoute('edit_password', array('person' => $session->get('user')), 301);
        }

        $user = new User();
        $error = "";
        $form = $this->createForm(new UserSessionType(), $user);
        if ($form->handleRequest($request)->isValid()) {

            $result = $ad->checkSession($user->getLogin(), $user->getPassword());
            if ($result) {
                $session->set('user', $ad->base64Encode($user->getLogin()));
                $adUser = $ad->getUserByUserPrincipalName($user->getLogin());
                $user->init($adUser);

                //if ($ad->checkAccessAdmin($user->getLogin())) {

                if ($user->getAccess() == 1 || in_array($user->getLogin(), $ad->getAuthorized())) {
                    $session->set('username', $user->getFullName());
                    $session->set('dn', $ad->base64Encode($user->getDn()));
                    return $this->redirectToRoute('dashboard', array(), 301);
                }
                return $this->redirectToRoute('edit_password', array('person' => $ad->base64Encode($user->getLogin())), 301);
            }
            $error = " Email ou Mot de passe <b>Incorrect</b>.";
        }
        return $this->render('ADBundle:AD:login.html.twig', array(
            'form' => $form->createView(),
            "error" => $error,
        ));
    }

    /**
     * @Route("/list-users.html", name="list_users")
     * @return RedirectResponse|Response
     */
    function listUsersAction()
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $users = $this->get("ad_active_directory")->getAllUser();
        return $this->render('ADBundle:AD:list_users.html.twig', array(
            "users" => $users,
        ));
    }

    /**
     * @Route("/list-computers.html", name="list_computers")
     * @return RedirectResponse|Response
     */
    function listComputerAction()
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $computers = $ad->getAllComputer();
        return $this->render('ADBundle:AD:computers.html.twig', array(
            "computers" => $computers,
        ));
    }

    /**
     * @Route("/list-groups.html", name="list_groups")
     * @return RedirectResponse|Response
     */
    function listGroupAction()
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }
        $adGroups = $ad->getAllGroup();
        return $this->render('ADBundle:AD:groups.html.twig', array(
            "groups" => $adGroups,
        ));
    }

    /**
     * @Route("/users/{ou}", name="users_by_ou")
     * @param $ou
     * @return Response
     */
    function UsersByOuAction($ou)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $tabOU = array("Issy-Les-Moulineaux", "Saint-mande", "Luxembourg", "Compteutilisateur", "Compteutilisateur");


        if (in_array($ou, $tabOU)) {

            $users = $ad->getAllUser($ou);

        } else {
            $users = $ad->getAllUser();
        }
        return $this->render('ADBundle:AD:list_users.html.twig', array(
            "users" => $users,
            "ou" => $ou
        ));
    }

    /**
     * @Route("/users/filter-{filter}.html", name="users_filter")
     * @param $filter
     * @return Response
     */
    function UsersFilterAction($filter)
    {


        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }
        $users = null;
        $ou = null;

        if ($filter === "locked") {
            $users = $ad->getUserInfoComputer("locked");
            $ou = "Bloqués";

        } else if ($filter === "disabled") {
            $users = $ad->getUserInfoComputer("disabled");
            $ou = "Désactivés";

        } else if ($filter === "expires") {
            $users = $ad->getUserInfoComputer("expires");
            $ou = "Expirés";
        }

        return $this->render('ADBundle:AD:list_users.html.twig', array(
            "users" => $users,
            "ou" => $ou,
        ));
    }

    /**
     * @Route("/user/{login}", name="get_user")
     * @param $login
     * @return RedirectResponse
     */
    function getUserAction($login)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $user = $ad->getUser($login);
        return $this->render('ADBundle:Default:index.html.twig', array(
            "users" => $user,
        ));
    }

    /**
     * @Route("/add_user.html", name="add_user")
     * @param Request $request
     * @return Response
     */
    function addUserAction(Request $request)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $result = null;
        $adGroups = $ad->getAllGroup();
        $data["groups"] = $adGroups;
        $user = new User();
        $user->setAddress("Bis, 2 Avenue Foch");
        $user->setPostalCode("94160");
        $user->setCity("Saint-Mandé");
        $user->setCountry("France");

        $form = $this->createForm(new UserType(), $user);
        $data ['form'] = $form->createView();

        if ($form->handleRequest($request)->isValid()) {
            $result = $ad->addUser($user);
            $data["result"] = $result;
        }
        return $this->render('ADBundle:AD:add-user.html.twig', $data);
//      return $this->render('ADBundle:Default:add.html.twig', $data);

    }

    /**
     * @Route("/user/{person}/remove.html", name="remove_user")
     * @param $person
     * @return Response
     */
    function removeUserAction($person)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $ad->removeUser($person);
        return $this->redirectToRoute('list_users');
    }

    /**
     * @Route("/user-edit/{person}", name="edit_user")
     * @param $person
     * @param Request $request
     * @return Response
     */
    function editUserAction(Request $request, $person)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $person = $ad->base64Decode($person);
        $adUser = $ad->getUser($person);
        $user = new User();
        $user = $user->init($adUser);

        $form = $this->createForm(new UserEditType(), $user);
        $data = array('form' => $form->createView());
        $data['user'] = $user;
        $data['adUser'] = $adUser;
        $adGroups = $ad->getAllGroup();
        $data["groups"] = $adGroups;


        if ($request->getSession()->has('result-edit')) {
            $data['result'] = $request->getSession()->get('result-edit');
            $request->getSession()->remove('result-edit');
        }

        if ($form->handleRequest($request)->isValid()) {
            $result = $ad->editUser($user, $person);
            $data["result"] = $result !== null ? true : false;
            if ($result !== null) {
                $data['user'] = $result;
                $request->getSession()->set('result-edit', true);
                return $this->redirectToRoute('edit_user', array('person' => $ad->base64Encode($result->getDn())), 301);
            }
        }
        return $this->render('ADBundle:AD:edit-user.html.twig', $data);
    }

    /**
     * @Route("/edit/{person}.html", name="edit_password")
     * @param $person
     * @param Request $request
     * @return Response
     */
    function editPasswordUserAction(Request $request, $person)
    {
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }
        $ad = $this->get("ad_active_directory");
        $person = $ad->base64Decode($person);
        $adUser = $ad->getUserByUserPrincipalName($person);
        $data = array();
        $user = new User();
        $user = $user->init($adUser);
        if ($user->getAccess()) {
            return $this->redirectToRoute('dashboard', array(), 301);
        } else if (!empty($user->getDn()) && !$user->getAccess()) {
            $result = null;
            $form = $this->createForm(new UserEditPasswordType(), $user);
            $data['form'] = $form->createView();
            $data['user'] = $user;
            if ($form->handleRequest($request)->isValid()) {
                $result = $ad->changePasswordUser($user);
                $data["result"] = $result;
            }
        }
        return $this->render('ADBundle:AD:edit-pwd-user.html.twig', $data);
    }

    /**
     * @Route("/edit-info/user={person}.html", name="edit_info_user")
     * @param Request $request
     * @param $person
     * @return Response
     */
    function editInfoUserAction(Request $request, $person)
    {
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }
        $ad = $this->get("ad_active_directory");
        $person = $ad->base64Decode($person);
        $adUser = $ad->getUserByUserPrincipalName($person);
        $data = array();
        $user = new User();
        $user = $user->init($adUser);
        // $user->setTitle("Développeur");
        //$user->setPhone("063189133");
        if ($user->getAccess()) {
            return $this->redirectToRoute('dashboard', array(), 301);
        } elseif (!empty($user->getDn()) && !$user->getAccess()) {
            $result = null;
            $form = $this->createForm(new UserEditInfoType(), $user);
            $data['form'] = $form->createView();
            $data['user'] = $user;

            if ($request->getSession()->has('result-edit-info')) {
                $data['result'] = $request->getSession()->get('result-edit-info');
                $request->getSession()->remove('result-edit-info');
            }

            if ($form->handleRequest($request)->isValid()) {
                $result = $ad->editInfoUser($user, $person);
                $request->getSession()->set('result-edit-info', $result);
                return $this->redirectToRoute('edit_info_user', array('person' => $ad->base64Encode($person)), 301);
            }
        }
        return $this->render('ADBundle:AD:edit-info-user.html.twig', $data);
    }


    /**
     * @Route("/get-by-login/{login}", name="get_by_login")
     * @param $login
     * @return Response
     */
    function byLoginAction($login)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }
        $ad = $ad->getUserByLogin($login);
        $result = null;
        $user = new User();
        $user = $user->init($ad);
        dump($user);
        die;
    }

    /**
     * @Route("/logout", name="logout")
     */
    function logoutAction()
    {
        $this->get('session')->remove('user');
        $this->get('session')->remove('dn');
        $this->get('session')->clear();
        return $this->redirectToRoute('login', array(), 301);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function removeUserAjaxAction(Request $request)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }


        $result = false;
        $message = "Erreur XMLHttpRequest";
        if ($request->isXmlHttpRequest()) {
            $tree = $request->get('tree');
            $username = $request->get('username');
            $ad = $this->get("ad_active_directory");
            $result = $ad->removeUser($tree);
            if ($result) {
                $message = "<b>" . $username . "</b> a été supprimé.";
            } else {
                $message = "<b>" . $username . "</b> n'a pas pu être supprimé.";
            }
        }
        return new response (json_encode(array('result' => $result, "message" => $message)));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function removeUserGroupAjaxAction(Request $request)
    {
        $ad = $this->get("ad_active_directory");
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
            return $this->redirectToRoute('logout', array(), 301);
        }

        $result = false;
        $message = "Erreur XMLHttpRequest";
        if ($request->isXmlHttpRequest()) {
            $dnUser = $request->get('dnUser');
            $dnGroup = $request->get('dnGroup');
            $username = $request->get('username');
            $groupName = $request->get('groupName');
            $ad = $this->get("ad_active_directory");
            $result = $ad->removeUserGroup($dnGroup, $dnUser);
            if ($result) {
                $message = $username . " a été supprimé du groupe (" . $groupName . ".";
            } else {
                $message = $username . " n'a pas pu être supprimé du groupe .";
            }
        }
        return new response (json_encode(array('result' => $result, "message" => $message)));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function getUserAjaxAction(Request $request)
    {
        $result = false;
        $data = array();
        if ($request->isXmlHttpRequest()) {
            $username = $request->get('user');
            $ad = $this->get("ad_active_directory");
            $username = $ad->base64Decode($username);
            $adUser = $ad->getUser($username);
            if (!empty($adUser)) {
                $user = new User();
                $data["fullname"] = $user->getData($adUser, "cn");
                $data["email"] = $user->getData($adUser, "userprincipalname");
                $data["login"] = $user->getData($adUser, "samaccountname");
                $data["username"] = $user->getData($adUser, "userprincipalname");
                $data["address"] = $user->getData($adUser, "st");
                $data["villePostalCode"] = $user->getData($adUser, "l") . " " . $user->getData($adUser, "postalcode");
                $data["country"] = $user->getData($adUser, "c");
                $data["tel"] = $user->getData($adUser, "telephonenumber");
                $data["office"] = $user->getData($adUser, "title");
                $result = true;
            }


        }
        return new response (json_encode(array('result' => $result, "user" => $data)));
    }


    /**
     * @param $ad
     * @return RedirectResponse
     * @internal param Request $request
     */
    function checkSession($ad)
    {
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        } else if ($this->get('session')->has('user')) {
            if ($ad->checkAccessAdmin($ad->base64Decode($this->get('session')->get('user'))) == FALSE) {
                return $this->redirectToRoute('logout', array(), 301);
            }
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    function searchAjaxAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $username = $request->get('person');
        }
    }


}
