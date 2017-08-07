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
use GameScoreBundle\Form\PlayType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class PlayController extends Controller
{
    /*
     * Action methods
     */

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function viewAction(Play $play)
    {
        $plays = $this
            ->container
            ->get('play_service')
            ->getPlayedGames('single_play_id', $play->getId(), 1, null);

        return $this->render(
            'GameScoreBundle:play:view.html.twig',
            array(
                'plays' => $plays,
                'extended_mode' => true,
                'mode' => 'update'
            )
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $play = new Play();
        $play->setDate(new \DateTime('now'));

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
                return $this->redirectToRoute('game_score_play_view',
                    array(
                        'id' => $play->getId(),
                        'mode' => 'update'
                    )
                );
            }
        }
        return $this->render('GameScoreBundle:Play:form.html.twig',
            array('form' => $form->createView()));
    }

    public function updateAction(Request $request, Play $play)
    {
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
                    ->add('info', 'Partie mise à jour.');
                return $this->redirectToRoute(
                    'game_score_play_view',
                    array(
                        'id' => $play->getId()
                    )
                );
            }
        }
        return $this->render('GameScoreBundle:Play:form.html.twig',
            array('form' => $form->createView()));
    }

}