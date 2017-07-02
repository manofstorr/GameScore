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


class PlayerController extends Controller
{

    private $PlayerRepository;

    private function setPlayerRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayerRepository = $em->getRepository('GameScoreBundle:Player');
    }

    public function viewAction(int $player_id)
    {
        $this->setPlayerRepository();
        $player = $this->PlayerRepository->find($player_id);
        if ($player === null) {
            throw new NotFoundHttpException('Aucun joueur trouvé avec cet id : ' . $player_id);
        }

        $playedGames = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Play')
            ->getPlayedGamesyPlayer($player_id);

        return $this->render(
            'GameScoreBundle:player:view.html.twig',
            array(
                'player' => $player,
                'playedGames' => $playedGames
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
            )
        );
    }

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
                return $this->redirectToRoute('game_score_view_player',
                    array('player_id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }

    public function updateAction(Request $request, int $player_id)
    {
        $player = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Player')
            ->find($player_id);

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
                return $this->redirectToRoute('game_score_view_player',
                    array('player_id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }


}