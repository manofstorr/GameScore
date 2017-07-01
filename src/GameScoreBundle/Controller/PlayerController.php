<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 01/07/2017
 * Time: 09:52
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PlayerController extends Controller
{

    private $PlayerRepository;

    private function setPlayerRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayerRepository = $em->getRepository('GameScoreBundle:Player');
    }

    public function viewPlayerAction(int $player_id)
    {
        $this->setPlayerRepository();
        $player = $this->PlayerRepository->find($player_id);
        if ($player === null) {
            throw new NotFoundHttpException('Aucun joueur trouvé avec cet id : ' . $player_id);
        }
        return $this->render(
            'GameScoreBundle:player:view.html.twig',
            array(
                'player' => $player
            )
        );
    }

    public function playerCollectionAction($page)
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
            'GameScoreBundle:Player:playerCollection.html.twig',
            array(
                'playerCollection' => $PlayerCollection,
                'page' => $page,
            )
        );
    }


}