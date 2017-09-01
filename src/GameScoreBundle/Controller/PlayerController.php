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
use GameScoreBundle\Form\Type\PlayerType;
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
        // call play service
        $playService = $this
            ->container
            ->get('play_service');

        $limitOfPlayedGamesShown = $this->getParameter('limit_of_played_games_shown');
        $plays = $playService
            ->getPlayedGames('player_id', $player->getId(), 0, $limitOfPlayedGamesShown);

        $numberOfItemsMostPlayedGames = $this->getParameter('number_of_item_to_show_for_most_played_games_player_view');
        $mostPlayedGames = $playService
            ->getMostPlayedGamesByPlayer($player->getId(), $numberOfItemsMostPlayedGames);

        $totalPlayedGames = $this->getTotalOfPlayedGamesByPlayer($player);

        return $this->render(
            'GameScoreBundle:Player:view.html.twig',
            [
                'player'                  => $player,
                'limitOfPlayedGamesShown' => $limitOfPlayedGamesShown,
                'totalPlayedGames'        => $totalPlayedGames,
                'plays'                   => $plays,
                'mostPlayedGames'         => $mostPlayedGames,
            ]
        );
    }

    private function getTotalOfPlayedGamesByPlayer(Player $player)
    {
        $playedGames = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(['player' => $player]);
        $numberOfPlayedGames = count($playedGames);

        return $numberOfPlayedGames;
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function collectionAction($page)
    {
        if ($page === '') {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }
        $PlayerCollection = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Player')
            ->getPlayers($page);

        $alphabeticalIndex = $this
            ->container->get('alphabetical_pagination')
            ->getAlphabeticalPagination('player');

        return $this->render(
            'GameScoreBundle:Player:collection.html.twig',
            [
                'playerCollection' => $PlayerCollection,
                'page'             => $page,
                'alphapageArray'   => $alphabeticalIndex,
            ]
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
                    ['id' => $player->getId()]);
            }
        }

        return $this->render('GameScoreBundle:Player:form.html.twig',
            ['form' => $form->createView()]);
    }

    /*
     * Other methods
     */

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
                    ['id' => $player->getId()]);
            }
        }

        return $this->render('GameScoreBundle:Player:form.html.twig',
            ['form' => $form->createView()]);
    }

}

