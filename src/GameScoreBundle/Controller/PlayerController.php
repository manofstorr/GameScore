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
use GameScoreBundle\Entity\Score;
use GameScoreBundle\Form\PlayerType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class PlayerController extends Controller
{

    private $PlayerRepository;


    /*
     * Action methods
     */

    public function viewAction(Player $player)
    {
        $limitOfPlayedGamesShown = $this->getParameter('limit_of_played_games_shown');
        // find played games with scores
        $lastPlayedGames = $this->getPlayedGamesByPlayer($player, $limitOfPlayedGamesShown);
        $totalPlayedGames = $this->getTotalOfPlayedGamesByPlayer($player);

        return $this->render(
            'GameScoreBundle:player:view.html.twig',
            array(
                'player' => $player,
                'playedGames' => $lastPlayedGames,
                'limitOfPlayedGamesShown' => $limitOfPlayedGamesShown,
                'totalPlayedGames' => $totalPlayedGames
            )
        );
    }

    public function collectionAction($page)
    {
        // Todo : make better condition
        if ($page === '') {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }
        $this->setPlayerRepository();
        $PlayerCollection = $this->PlayerRepository->getPlayers($page);
        if ($PlayerCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de joueurs.');
        }
        return $this->render(
            'GameScoreBundle:Player:collection.html.twig',
            array(
                'playerCollection' => $PlayerCollection,
                'page' => $page,
                'alphapageArray' => $this->getAlphaIndex()
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

    private function setPlayerRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayerRepository = $em->getRepository('GameScoreBundle:Player');
    }

    public function getAlphaIndex()
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Player');
        $players = $em->findAll();
        // building initial array for alphabetical pseudo-pagination
        $alphapageArray = array();
        foreach ($players as $player) {
            $index = substr($player->getFirstname(), 0, 1);
            if (!in_array($index, $alphapageArray)) {
                $alphapageArray[] = $index;
            }
        }
        return $alphapageArray;
    }

    private function getPlayedGamesByPlayer(Player $player, $limit)
    {
        $playedGames = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Play')
            ->getPlayedGamesByPlayer($player->getId(), $limit, null);
        return $playedGames;
    }

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