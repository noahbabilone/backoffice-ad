<?php

namespace ADBundle\Controller;

use ADBundle\Entity\User;
use ADBundle\Form\UserSessionType;
use ADBundle\Form\UserType;
use ADBundle\Form\UserEditType;
use ADBundle\Form\UserEditPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;


class ADController extends Controller
{
    protected $accessControl = array('administrator', "yannick.said@42consulting.fr");
    //protected $accessControl = array('administrator');
    /**
     * @Route("/",name="login")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function loginAction(Request $request)
    {
        $ad = $this->get("ad_active_directory");
        if ($request->getSession()->has('user')) {
            if (in_array($ad->base64Decode($request->getSession()->get('user')), $this->accessControl)) {
                return $this->redirectToRoute('list_users');
            }
            return $this->redirectToRoute('edit_password', array('person' => $request->getSession()->get('user')), 301);
        }

        $user = new User();
        $error = "";
        $form = $this->createForm(new UserSessionType(), $user);
        if ($form->handleRequest($request)->isValid()) {

            $result = $ad->checkSession($user->getLogin(), $user->getPassword());
            if ($result) {
                $request->getSession()->set('user', $ad->base64Encode($user->getLogin()));
                if (in_array(strtolower($user->getLogin()), $this->accessControl)) {
                    return $this->redirectToRoute('list_users');
                }

                return $this->redirectToRoute('edit_password', array('person' => $ad->base64Encode($user->getLogin())), 301);
            }
            $error = " Email ou Mot de passe <b>Incorrect</b>.";
        }
        return $this->render('ADBundle:Default:login.html.twig', array(
            'form' => $form->createView(),
            "error" => $error,
        ));
    }

    /**
     * @Route("/admin",name="login_admin")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function loginAdminAction(Request $request)
    {
        $user = new User();
        $error = "";
        $form = $this->createForm(new UserSessionType(), $user);
        if ($form->handleRequest($request)->isValid()) {

            $ad = $this->get("ad_active_directory");
            $result = $ad->checkSession($user->getLogin(), $user->getPassword());
            if ($result) {
                $request->getSession()->set('user', $ad->base64Encode($user->getLogin()));
                return $this->redirectToRoute('list_users');
            }
            $error = " Login ou Mot de passe <b>Incorrect</b>.";
        }
        return $this->render('ADBundle:Default:login-1.html.twig', array(
            'form' => $form->createView(),
            "error" => $error,
        ));
    }

    /**
     * @Route("/account/list-users", name="list_users")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function listUsersAction(Request $request)
    {
        if (!$request->getSession()->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }
        $ad = $this->get("ad_active_directory");
        $users = $ad->getAllUser();
        return $this->render('ADBundle:Default:index.html.twig', array(
            "users" => $users,
        ));
    }

    /**
     * @Route("/account/users/{ou}", name="users_by_ou")
     * @param $ou
     * @return Response
     */
    public function UsersByOuAction($ou)
    {

        $this->checkSession();
        $ad = $this->get("ad_active_directory");

        $tabOU = array("Issy-Les-Moulineaux", "Saint-mande", "Luxembourg", "Compteutilisateur", "Compteutilisateur");
        if (in_array($ou, $tabOU)) {
            $users = $ad->getAllUser($ou);
        } else {
            $users = $ad->getAllUser();
        }
        return $this->render('ADBundle:Default:index.html.twig', array(
            "users" => $users,
        ));
    }

    /**
     * @Route("/user/{login}", name="get_user")
     * @param $login
     * @return
     */
    public function getUserAction($login)
    {
        $this->checkSession();
        $ad = $this->get("ad_active_directory");
        $user = $ad->getUser($login);

        dump($user);
        die;
        return $this->render('ADBundle:Default:index.html.twig', array(
            "users" => $user,
        ));
    }

    /**
     * @Route("/add_user.html", name="add_user")
     * @param Request $request
     * @return Response
     */
    public function addUserAction(Request $request)
    {
        $this->checkSession();
        $result = null;
        $user = new User();
        $form = $this->createForm(new UserType(), $user);
        $data = array('form' => $form->createView());
        if ($form->handleRequest($request)->isValid()) {
            $ad = $this->get("ad_active_directory");
            $result = $ad->addUser($user);
            $data["result"] = $result;
        }
        return $this->render('ADBundle:Default:add.html.twig', $data);
    }

    /**
     * @Route("/user/{person}/remove.html", name="remove_user")
     * @param $person
     * @return Response
     */
    public function removeUserAction($person)
    {
        $this->checkSession();
        $ad = $this->get("ad_active_directory");
        $ad->removeUser($person);
        return $this->redirectToRoute('list_users');
    }

    /**
     * @Route("/user-edit/{person}", name="edit_user")
     * @param $person
     * @param Request $request
     * @return Response
     */
    public function editUserAction(Request $request, $person)
    {
        if (!$request->getSession()->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }
        $ad = $this->get("ad_active_directory");
        $person = $ad->base64Decode($person);
        $adUser = $ad->getUser($person);
        $user = new User();
        $user = $user->init($adUser);

        $form = $this->createForm(new UserEditType(), $user);
        $data = array('form' => $form->createView());
        $data['user'] = $user;
        $data['users'] = $adUser;

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
        return $this->render('ADBundle:Default:edit.html.twig', $data);

    }


    /**
     * @Route("/change-password-active-directory/user={person}.html", name="edit_password")
     * @param $person
     * @param Request $request
     * @return Response
     */
    public function editPasswordUserAction(Request $request, $person)
    {
        if (!$request->getSession()->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }

        $ad = $this->get("ad_active_directory");
        $person = $ad->base64Decode($person);
        $adUser = $ad->getUserByUserPrincipalName($person);
        $data = array();
        $user = new User();

        $user = $user->init($adUser);

        if (!empty($user->getDn() && !in_array(strtolower($person), $this->accessControl))) {
            $result = null;
            $form = $this->createForm(new UserEditPasswordType(), $user);
            $data['form'] = $form->createView();
            $data['user'] = $user;
            if ($form->handleRequest($request)->isValid()) {
                $result = $ad->changePasswordUser($user);
                $data["result"] = $result;
            }
        }
        return $this->render('ADBundle:Default:edit-pwd-user.html.twig', $data);

    }

    /**
     * @Route("/get-by-login/{login}", name="get_by_login")
     * @param $login
     * @return Response
     */
    public function byLoginAction($login)
    {
        $ad = $this->get("ad_active_directory");
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
    public function disconnectionAction()
    {
        $this->get('session')->remove('user');
        $this->get('session')->clear();
        return $this->redirectToRoute('login', array(), 301);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function removeUserAjaxAction(Request $request)
    {
        $result = false;
        $message = "Erreur XMLHttpRequest";
        if ($request->isXmlHttpRequest()) {
            $tree = $request->get('tree');
            $username = $request->get('username');
            $ad = $this->get("ad_active_directory");
            $result = $ad->removeUser($tree);
            if ($result) {
                $message = $username . " a été supprimé.";
            } else {
                $message = $username . " n'a pas pu être supprimé.";
            }
        }
        return new response (json_encode(array('result' => $result, "message" => $message)));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getUserAjaxAction(Request $request)
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
                $data["login"] = $user->getData($adUser, "samaccountname");
                $data["username"] = $user->getData($adUser, "userprincipalname");
                $data["address"] = $user->getData($adUser, "streetaddress");
                $data["villePostalCode"] = $user->getData($adUser, "l") . " " . $user->getData($adUser, "postalcode");
                // $data["country"] =$user->getData($adUser,"c");
                $data["tel"] = $user->getData($adUser, "telephonenumber");
                $data["tel"] = $user->getData($adUser, "physicaldeliveryofficename");
                $result = true;
            }


        }
        return new response (json_encode(array('result' => $result, "user" => $data)));
    }


    /**
     * @return RedirectResponse
     */
    protected function  checkSession()
    {
        if (!$this->get('session')->has('user')) {
            return $this->redirectToRoute('login', array(), 301);
        }
    }


}
