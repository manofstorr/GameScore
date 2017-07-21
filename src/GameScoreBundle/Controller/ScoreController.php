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
                    return $this->redirectToRoute('game_score_homepage');
                }
            }
        }
        return $this->render('GameScoreBundle:Score:form.html.twig',
            array(
                'form' => $form->createView(),
                'play' => $play,
                'previous_scores' => $this->getScoresByGame($play),
            )
        );
    }

    public function getScoresByGame($play)
    {
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(array('play' => $play));
    }

}