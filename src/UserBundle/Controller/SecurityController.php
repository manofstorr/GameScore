<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 03/07/2017
 * Time: 13:52
 */

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
            return $this->redirectToRoute('game_score_homepage');
        }

        $authenticationUtils = $this->get('security.authentication_utils');
        return $this->render('UserBundle:Security:login.html.twig',
            array(
                'last_username' => $authenticationUtils->getLastUsername(),
                'error' => $authenticationUtils->getLastAuthenticationError()
            )
        );
    }
}