<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 05/07/2017
 * Time: 22:26
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form\UserType;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    /*
    private $UserRepository;

    private function setUserRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->UserRepository = $em->getRepository('UserBundle:User');
    }

    findUserByUsername($username)
findUserByEmail($email)
findUserByUsernameOrEmail($value) (check if the value looks like an email to choose)
findUserByConfirmationToken($token)
findUserBy(array('id'=>$id))
findUsers()
    */
    public function createAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Utilisateur ajouté !');
                return $this->redirectToRoute('user_view',
                    array('id' => $user->getId()));

            }
        }

        return $this->render('UserBundle:User:form.html.twig',
            array('form' => $form->createView()));
    }

    public function updateAction(Request $request, int $id) {

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));

        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Utilisateur mis à jour.');
                return $this->redirectToRoute('user_view',
                    array('id' => $user->getId()));
            }

        }

        return $this->render('UserBundle:User:form.html.twig',
            array('form' => $form->createView()));

    }

    public function collectionAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        return $this->render('UserBundle:User:collection.html.twig',
            array('users' => $users)
        );
    }

    public function viewAction(int $id)
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => $id));
        return $this->render('UserBundle:User:view.html.twig',
            array('user' => $user));
    }
}

/*
 * more...
 * // Dans un contrôleur :


// Pour récupérer le service UserManager du bundle

$userManager = $this->get('fos_user.user_manager');


// Pour charger un utilisateur

$user = $userManager->findUserBy(array('username' => 'winzou'));


// Pour modifier un utilisateur

$user->setEmail('cetemail@nexiste.pas');

$userManager->updateUser($user); // Pas besoin de faire un flush avec l'EntityManager, cette méthode le fait toute seule !


// Pour supprimer un utilisateur

$userManager->deleteUser($user);


// Pour récupérer la liste de tous les utilisateurs

$users = $userManager->findUsers();
 */