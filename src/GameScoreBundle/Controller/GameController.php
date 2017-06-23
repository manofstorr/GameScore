<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 18/06/2017
 * Time: 14:46
 * --
 * A controllers for Games
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameController extends Controller
{

    public function gamesCollectionAction()
    {
        // todo : real collection
        $gamesCollection = array(1, 2, 3);
        return $this->render(
            'GameScoreBundle:Game:gamesCollection.html.twig',
            array(
                'gamesColection' => $gamesCollection
            )
        );
    }


    public function readGameCardAction($game_id)
    {
        $em = $this->getDoctrine()->getManager();
        $gameRepository = $em->getRepository('GameScoreBundle:Game');
        $game = $gameRepository->find($game_id);

        if ($game === null) {
            throw new NotFoundHttpException('No game with ' . $game_id . ' id found.');
        }

        return $this->render(
            'GameScoreBundle:Game:readGameCard.html.twig',
            array(
                'game' => $game
            )
        );
    }


    /* CRUD ****************************************************** */

    public function createGameCardAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'Create... more');
            return $this->redirectToRoute('game_score_view_game_card', array('game_id' => 27));
        }
        return $this->render('GameScoreBundle:Game:Create.html.twig');
    }

    public function updateGameCardAction()
    {
    }

    public function deleteGameCardAction()
    {
    }


}