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
use GameScoreBundle\Collections;

class GameController extends Controller
{

    private $gameRepository;

    private function setGameRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->gameRepository = $em->getRepository('GameScoreBundle:Game');
    }

    public function gamesCollectionAction()
    {
        $this->setGameRepository();
        $gameCollection = $this->gameRepository->findAll();

        if ($gameCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de jeux.');
        }

        return $this->render(
            'GameScoreBundle:Game:gamesCollection.html.twig',
            array(
                'gameCollection' => $gameCollection
            )
        );
    }


    public function readGameCardAction($game_id)
    {
        $this->setGameRepository();
        $game = $this->gameRepository->find($game_id);

        if ($game === null) {
            throw new NotFoundHttpException('Aucun jeu trouvé avec cet id : ' . $game_id);
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