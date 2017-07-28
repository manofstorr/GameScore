<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 01/07/2017
 * Time: 09:52
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameScoreBundle\Entity\Player;
use GameScoreBundle\Form\PlayerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayerController extends Controller
{
    /*
     * Action methods
     */

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function viewAction(Player $player)
    {
        $limitOfPlayedGamesShown = $this->getParameter('limit_of_played_games_shown');
        $totalPlayedGames = $this->getTotalOfPlayedGamesByPlayer($player);

        // new way to do :
        // call play service
        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('player_id', $player->getId(), 10, null);

        return $this->render(
            'GameScoreBundle:player:view.html.twig',
            array(
                'player' => $player,
                'limitOfPlayedGamesShown' => $limitOfPlayedGamesShown,
                'totalPlayedGames' => $totalPlayedGames,
                'plays' => $plays
            )
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function collectionAction($page)
    {
        // Todo : make better condition
        if ($page === '') {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }
        $PlayerCollection = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Player')
            ->getPlayers($page);
        if ($PlayerCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de joueurs.');
        }

        $alphabeticalIndex = $this
            ->container->get('alphabetical_pagination')
            ->getAlphabeticalPagination('player');

        return $this->render(
            'GameScoreBundle:Player:collection.html.twig',
            array(
                'playerCollection' => $PlayerCollection,
                'page' => $page,
                'alphapageArray' => $alphabeticalIndex
            )
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $player = new Player();
        $form = $this->createForm(PlayerType::class, $player);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Joueur ajouté !');
                return $this->redirectToRoute('game_score_player_view',
                    array('id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, Player $player)
    {
        $form = $this->createForm(PlayerType::class, $player);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($player);
                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Joueur mis à jour.');
                return $this->redirectToRoute('game_score_player_view',
                    array('id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }

    /*
     * Other methods
     */

    private function getTotalOfPlayedGamesByPlayer(Player $player)
    {
        $playedGames = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(array('player' => $player));
        $numberOfPlayedGames = count($playedGames);
        return $numberOfPlayedGames;
    }

}