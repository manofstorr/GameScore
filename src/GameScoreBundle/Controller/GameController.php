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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class GameController extends Controller
{

    public function collectionAction($page = 'a')
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');

        $gameCollection = $em->getGames($page);

        if ($gameCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de jeux.');
        }

        $alphabeticalIndex = $this
            ->container->get('alphabetical_pagination')
            ->getAlphabeticalPagination('game');

        return $this->render(
            'GameScoreBundle:Game:collection.html.twig',
            array(
                'gameCollection' => $gameCollection,
                'page' => $page,
                'alphapageArray' => $alphabeticalIndex
            )
        );
    }

    public function viewAction(Game $game)
    {
        // find last plays
        $limitOfPlayedGamesShown = $this->getParameter('limit_of_played_games_shown');
        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('game_id', $game->getId(), $limitOfPlayedGamesShown, null);

        // count played games all times
        $totalPlayedGames = count(
            $this
                ->getDoctrine()
                ->getManager()
                ->getRepository('GameScoreBundle:Play')
                ->findBy(
                    array('game' => $game)
            )
        );

        return $this->render(
            'GameScoreBundle:Game:view.html.twig',
            array(
                'game' => $game,
                'plays' => $plays,
                'extended_mode' => true,
                'totalPlayedGames' => $totalPlayedGames,
                'mode' => 'view'

            )
        );
    }

    /* CRUD ****************************************************** */

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
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
                return $this->redirectToRoute('game_score_game_view',
                    array('game_id' => $game->getId()));
            }
        }

        return $this->render('GameScoreBundle:Game:form.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, Game $game)
    {
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
                return $this->redirectToRoute('game_score_game_view',
                    array('id' => $game->getId()));
            }
        }
        return $this->render('GameScoreBundle:Game:form.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteGameAction()
    {
    }


}