<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 01/07/2017
 * Time: 11:18
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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

    public function collectionAction()
    {

    }

}