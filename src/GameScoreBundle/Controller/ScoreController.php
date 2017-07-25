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
    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request, Play $play)
    {
        $score = new Score();
        $form = $this->createForm(ScoreType::class, $score, array('play_id' => $play->getId()));

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($score);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Score ajouté !');

                if (isset($_POST['gamescorebundle_score']['save_and_stop'])) {
                    return $this->redirectToRoute('game_score_game_view', array('id' => $play->getGame()->getId()));
                }
            }
        }
        // play info via service
        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('single_play_id', $play->getId(), 1, null);

        return $this->render('GameScoreBundle:Score:form.html.twig',
            array(
                'form' => $form->createView(),
                'play' => $play,
                'scores' => $this->getScoresByGame($play),
                'plays' => $plays
            )
        );
    }

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

}