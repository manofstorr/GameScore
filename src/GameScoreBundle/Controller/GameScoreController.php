<?php
/**
 * Created by PhpStorm.
 * User: Actif
 *
 * Date: 23/06/2017
 * Time: 17:29
 *
 * Controller for index and user forms
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

class GameScoreController extends Controller
{

    public function indexAction()
    {
        $user = $this->getUser();

        return $this->render(
            'GameScoreBundle:GameScore:index.html.twig',
            array(
                'user' => $user,
            )
        );
    }

}