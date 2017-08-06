<?php
/**
 * Created by PhpStorm.
 * User: talend
 * Date: 12/07/2017
 * Time: 18:10
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameScoreBundle\Entity\Score;
use GameScoreBundle\Entity\Play;
use GameScoreBundle\Form\ScoreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ScoreController extends Controller
{

    /*
     * Action methods
     */

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request, Play $play)
    {
        $score = new Score();

        if ($request->isMethod('POST')) {

            // exit if asked
            if (isset($_POST['gamescorebundle_score']['stop_and_exit'])) {
                return $this->redirectToRoute('game_score_play_view',
                    array(
                        'id' => $play->getId(),
                        'mode' => 'update'
                    )
                );
            }

            $form = $this->createForm(
                ScoreType::class,
                $score,
                array(
                    'play_id' => $play->getId(),
                    'players_out' => array(0),
                )
            );
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($score);
                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Score ajouté !');
            }
        }

        // after persist use service to catch play view
        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('single_play_id', $play->getId(), 1, null);

        // redeclare form without players yet scored (need to be done after persist)
        $form = $this->createForm(
            ScoreType::class,
            $score,
            array(
                'play_id' => $play->getId(),
                'players_out' => $this->getPlayersYetInthePlay($play),
            )
        );

        return $this->render('GameScoreBundle:Score:form.html.twig',
            array(
                'form' => $form->createView(),
                'play' => $play,
                'plays' => $plays,
                'mode' => 'update'
            )
        );
    }

    public function updateAction(Request $request, Score $score)
    {
        // declare form for check
        $form = $this->createForm(
            ScoreType::class,
            $score,
            array(
                'play_id' => $score->getPlay()->getId(),
                'players_out' => array(0),
            )
        );

        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('single_play_id', $score->getPlay()->getId(), 1, null);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($score);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Score mis à jour.');

                return $this->render(
                    'GameScoreBundle:play:view.html.twig',
                    array(
                        'plays' => $plays,
                        'extended_mode' => true,
                        'mode' => 'update'
                    )
                );
            }
        }

        return $this->render('GameScoreBundle:Score:form.html.twig',
            array(
                'form' => $form->createView(),
                'play' => $score->getPlay(),
                'plays' => $plays
            )
        );
    }

    /*
     * Other methods
     */
    public function getScoresByGame($play)
    {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(
                array('play' => $play),
                array('score' => 'desc')
            );
    }

    public function getPlayersYetInthePlay($play)
    {
        // retrieve players yet in the plays > they wont't be proposed by the form
        $PlayersYetInThePlay = array(0);
        $getPlayersYetInthePlay = $this->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(array('play' => $play));
        foreach ($getPlayersYetInthePlay as $line) {
            array_push($PlayersYetInThePlay, $line->getPlayer()->getId());
        }
        return $PlayersYetInThePlay;
    }

}