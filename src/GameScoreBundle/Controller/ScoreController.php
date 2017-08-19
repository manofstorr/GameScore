<?php
/**
 * Created by PhpStorm.
 * User: talend
 * Date: 12/07/2017
 * Time: 18:10
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use GameScoreBundle\Entity\Score;
use GameScoreBundle\Entity\Play;
use GameScoreBundle\Form\Type\ScoreType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ScoreController extends Controller
{
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request, Play $play)
    {
        $score = new Score();

        // test presence of stop command
        $posted = filter_input_array(INPUT_POST);
        $stop = (isset($posted['gamescorebundle_score']['stop_and_exit']));

        if ($request->isMethod('POST')) {
            if ($stop) {
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

        // after persist use service to catch play view and players yed declared
        $playService = $this
            ->container
            ->get('play_service');
        $plays = $playService->getPlayedGames('single_play_id', $play->getId(), 1, null);
        $playersYetInThePlay = $playService->getPlayersYetInthePlay($play);

        // redeclare form without players yet scored (need to be done after persist)
        $form = $this->createForm(
            ScoreType::class,
            $score,
            array(
                'play_id' => $play->getId(),
                'players_out' => $playersYetInThePlay,
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
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
                    'GameScoreBundle:Play:view.html.twig',
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

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function deleteAction($id)
    {
        $repository = $this->getDoctrine()->getRepository('GameScoreBundle:Score');
        $score = $repository->find($id);

        $play_id = $score->getPlay()->getId();

        // delete here
        $em = $this->getDoctrine()->getManager();
        $em->remove($score);
        $em->flush();

        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('single_play_id', $play_id, 1, null);

        // back to play view
        return $this->render(
            'GameScoreBundle:Play:view.html.twig',
            array(
                'plays' => $plays,
                'extended_mode' => true,
                'mode' => 'update'
            )
        );
    }
}

