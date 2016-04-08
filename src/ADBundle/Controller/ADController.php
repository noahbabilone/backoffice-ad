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

class ADController extends Controller
{
    /**
     * @Route("/",name="login")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function loginAction(Request $request)
    {
        $user = new User();
        $error = "";
        $form = $this->createForm(new UserSessionType(), $user);
        if ($form->handleRequest($request)->isValid()) {


            $ad = $this->get("ad_active_directory");
            $result = $ad->checkSession($user->getLogin(), $user->getPassword());
            if ($result) {
                //A tester 
                $session = $request->getSession();
                $session->set('login', $user->getLogin());
                $session->set('pass', $user->getLogin());
                $session->set('user', $user);

                return $this->redirectToRoute('list_users');
            }
            $error = " Login ou Mot de passe <b>Incorrect</b>.";

        }
        return $this->render('ADBundle:Default:login.html.twig', array(
            'form' => $form->createView(),
            "error" => $error,
        ));
    }

    /**
     * @Route("/users", name="list_users")
     */
    public function listUsersAction()
    {
        $ad = $this->get("ad_active_directory");
        $users = $ad->getAllUser();
        return $this->render('ADBundle:Default:index.html.twig', array(
            "users" => $users,
        ));
    }

    /**
     * @Route("/users/{ou}", name="users_by_ou")
     */
    public function UsersByOuAction($ou)
    {
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
     */
    public function getUserAction($login)
    {
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
        $ad = $this->get("ad_active_directory");
        $ad = $ad->getUser($person);
//
        $result = null;
        $user = new User();
        $user = $user->init($ad);
        $form = $this->createForm(new UserEditType(), $user);
        $data = array('form' => $form->createView(), 'user' => $user);
        if ($form->handleRequest($request)->isValid()) {
            $ad = $this->get("ad_active_directory");
            $result = $ad->editUser($user);
            $data["result"] = $result;

        }
        return $this->render('ADBundle:Default:edit.html.twig', $data);

    }


    /**
     * @Route("/user-edit-pwd/{person}", name="edit_password_user")
     * @param $person
     * @param Request $request
     * @return Response
     */
    public function editPasswordAction(Request $request, $person)
    {
        $ad = $this->get("ad_active_directory");
        $adUser = $ad->getUserByLogin($person);
        $data = array();
        $user = new User();
        $user = $user->init($adUser);

        if (!empty($user->getDn())) {
            $result = null;
            $form = $this->createForm(new UserEditPasswordType(), $user);
            $data['form'] = $form->createView();
            $data['user'] = $user;
            if ($form->handleRequest($request)->isValid()) {
                $ad = $this->get("ad_active_directory");
                $result = $ad->changePasswordUser($user);
                $data["result"] = $result;
            }
        }
        return $this->render('ADBundle:Default:edit-pwd.html.twig', $data);

    }

    /**
     * @Route("/ad-edit-pwd/{person}", name="edit_password")
     * @param $person
     * @param Request $request
     * @return Response
     */
    public function editPasswordUserAction(Request $request, $person)
    {
        $ad = $this->get("ad_active_directory");
        $adUser = $ad->getUserByLogin($person);
        $data = array();
        $user = new User();
        $user = $user->init($adUser);

        if (!empty($user->getDn())) {
            $result = null;
            $form = $this->createForm(new UserEditPasswordType(), $user);
            $data['form'] = $form->createView();
            $data['user'] = $user;
            if ($form->handleRequest($request)->isValid()) {
                $ad = $this->get("ad_active_directory");
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
    public function checkPasswordAjaxAction(Request $request)
    {
        $result = false;
        $message = "Erreur XMLHttpRequest";
        if ($request->isXmlHttpRequest()) {
            $login = $request->get('login');
            $password = $request->get('login');
            $ad = $this->get("ad_active_directory");
            $result = $ad->checkSession($login, $password);
        }
        return new response (json_encode(array('result' => $result, "message" => $message)));
    }

}
