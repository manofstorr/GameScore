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


class PlayController extends Controller
{
    private $PlayRepository;

    private function setPlayRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->PlayRepository = $em->getRepository('GameScoreBundle:Play');
    }

    public function viewAction($play_id)
    {
        $this->setPlayRepository();
        $play = $this->PlayRepository->find($play_id);
        if ($play === null) {
            throw new NotFoundHttpException('Aucune prtie de jeu (play) trouvée avec cet id : ' . $play_id);
        }
        return $this->render(
            'GameScoreBundle:play:view.html.twig',
            array(
                'play' => $play
            )
        );
    }


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
                return $this->redirectToRoute('game_score_play_view',
                    array('id' => $play->getId()));
            }
        }
        return $this->render('GameScoreBundle:Play:form.html.twig',
            array('form' => $form->createView()));
    }


    public function collectionAction()
    {

    }

}