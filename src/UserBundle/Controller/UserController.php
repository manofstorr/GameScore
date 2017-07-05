<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 05/07/2017
 * Time: 22:26
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GameScoreBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    public function userManageAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id' => 1));
        return $this->render('UserBundle:User:usermanage.html.twig',
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