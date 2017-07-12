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

    public function createAction(Request $request, $id)
    {

        $play = $this
            ->getDoctrine()->getManager()
            ->getRepository('GameScoreBundle:Play')
            ->find($id);

        $score = new Score();
        $form = $this->createForm(ScoreType::class, $score, array('play_id' => $id));

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
                return $this->redirectToRoute('game_score_score_create',
                    array('id' => $play->getId()));
            }
        }
        return $this->render('GameScoreBundle:Score:form.html.twig',
            array(
                'form' => $form->createView(),
                'play' => $play
            )
        );
    }

}