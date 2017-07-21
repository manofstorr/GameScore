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

    private function setPlayerRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayerRepository = $em->getRepository('GameScoreBundle:Player');
    }

    public function viewAction(int $player_id, $page=1)
    {
        $this->setPlayerRepository();
        $player = $this->PlayerRepository->find($player_id);
        if ($player === null) {
            throw new NotFoundHttpException('Aucun joueur trouvé avec cet id : ' . $player_id);
        }
        $nbPerPage = ($this->container->getParameter('standard_number_of_elements_per_page'))*2;
        $limit = ($page-1)*$nbPerPage;

        $playedGames = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Play')
            ->getPlayedGamesyPlayer($player_id, $limit, $nbPerPage);

        // todo : other function
        $em = $this->getDoctrine()->getManager();
        $scoreRepository = $em->getRepository('GameScoreBundle:Score');
        // todo : find better way to count
        $totalNumberofPlayedGames =  count($scoreRepository->findBy(array('player' => $player)));
        $nbOfPages = ceil($totalNumberofPlayedGames/$nbPerPage);

        return $this->render(
            'GameScoreBundle:player:view.html.twig',
            array(
                'player' => $player,
                'playedGames' => $playedGames,
                'page' => $page,
                'nbOfPages' => $nbOfPages,
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

    public function getAlphaIndex()
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Player');
        $players = $em->findAll();
        // building initial array for alphabetical pseudo-pagination
        $alphapageArray = array();
        foreach ($players as $player){
            $index = substr($player->getFirstname(), 0,1);
            if (!in_array($index, $alphapageArray)){
                $alphapageArray[] = $index;
            }
        }
        return $alphapageArray;
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
                    array('player_id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
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
                return $this->redirectToRoute('game_score_player_view',
                    array('player_id' => $player->getId()));
            }
        }
        return $this->render('GameScoreBundle:Player:form.html.twig',
            array('form' => $form->createView()));
    }


}