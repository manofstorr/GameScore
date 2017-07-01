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

use GameScoreBundle\Entity\Game;
use GameScoreBundle\Entity\Play;
use GameScoreBundle\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class GameController extends Controller
{

    public function gameCollectionAction($page='a')
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');

        $gameCollection = $em->getGames($page);
        //$gameCollection = $em->findAll();

        if ($gameCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de jeux.');
        }

        return $this->render(
            'GameScoreBundle:Game:gameCollection.html.twig',
            array(
                'gameCollection' => $gameCollection,
                'page' => $page
            )
        );
    }


    public function readGameAction($game_id)
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');
        $game = $em->find($game_id);

        if ($game === null) {
            throw new NotFoundHttpException('Aucun jeu trouvé avec cet id : ' . $game_id);
        }

        // find plays
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Play');
        $plays = $em->findPlayByGame($game);

        return $this->render(
            'GameScoreBundle:Game:readGame.html.twig',
            array(
                'game' => $game,
                'plays' => $plays
            )
        );
    }

    /* CRUD ****************************************************** */

    public function createGameAction(Request $request)
    {
        $game = new Game();
        $form = $this->createForm(GameType::class, $game);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Le jeu a bien été créé.');
                return $this->redirectToRoute('game_score_view_game',
                    array('game_id' => $game->getId()));
            }

        }

        return $this->render('GameScoreBundle:Game:form.html.twig',
            array('form' => $form->createView()));
    }

    public function updateGameAction(Request $request, int $game_id)
    {
        $game = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game')
            ->find($game_id);

        $form = $this->createForm(GameType::class, $game);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Le jeu a bien été mis à jour.');
                return $this->redirectToRoute('game_score_view_game',
                    array('game_id' => $game->getId()));
            }
        }

        return $this->render('GameScoreBundle:Game:form.html.twig',
            array('form' => $form->createView()));
    }

    public function deleteGameAction()
    {
    }


}