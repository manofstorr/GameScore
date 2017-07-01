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

    public function playerCollectionAction($page=1)
    {
        $this->setPlayerRepository();

        $nbPerPage = $this->container->getParameter('standard_number_of_elements_per_page');

        if ($page < 1) {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }

        //$PlayerCollection = $this->PlayerRepository->getEditors($page, $nbPerPage);
        $PlayerCollection = $this->PlayerRepository->findAll();
        // todo : put this in a service ?
        //$nbOfPages = ceil($PlayerCollection->count() / $nbPerPage);
        /*
        if ($page > $nbOfPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }
        */

        if ($PlayerCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de joueurs.');
        }

        return $this->render(
            'GameScoreBundle:Player:playerCollection.html.twig',
            array(
                'playerCollection' => $PlayerCollection,
                'nbOfPages'     => 1,
                'page'        => 1,
            )
        );
    }


}