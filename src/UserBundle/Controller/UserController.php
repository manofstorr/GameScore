<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 05/07/2017
 * Time: 22:26
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use UserBundle\Form\Type\UserType;
use UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserController extends Controller
{
    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function updateAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $this
                    ->get('fos_user.user_manager')
                    ->updateUser($user);
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function collectionAction()
    {
        $userManager = $this->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        return $this->render('UserBundle:User:collection.html.twig',
            array('users' => $users)
        );
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewAction(User $user)
    {
        return $this->render('UserBundle:User:view.html.twig',
            array('user' => $user));
    }
}


