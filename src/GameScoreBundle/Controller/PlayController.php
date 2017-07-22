<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 01/07/2017
 * Time: 11:18
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use GameScoreBundle\Entity\Play;
use GameScoreBundle\Controller\ScoreController;
use GameScoreBundle\Form\PlayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayController extends Controller
{
    private $PlayRepository;

    private function setPlayRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayRepository = $em->getRepository('GameScoreBundle:Play');
    }

    public function viewAction(Play $play)
    {
        return $this->render(
            'GameScoreBundle:play:view.html.twig',
            array(
                'play' => $play,
                'scores' => $this->getScores($play)
            )
        );
    }

    private function getScores(Play $play)
    {
        // todo: How ti use directly the ScoreController method ?
        return $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Score')
            ->findBy(
                array('play' => $play),
                array('score' => 'desc')
            );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $play = new Play();
        $form = $this->createForm(PlayType::class, $play);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($play);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Partie ajoutée !');
                return $this->redirectToRoute('game_score_score_create',
                    array(
                        'id' => $play->getId(),
                    )
                );
            }
        }
        return $this->render('GameScoreBundle:Play:form.html.twig',
            array('form' => $form->createView()));
    }


    public function collectionAction()
    {

    }

}