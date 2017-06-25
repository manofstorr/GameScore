<?php
/**
 * Created by PhpStorm.
 * User: David
 * Date: 18/06/2017
 * Time: 14:46
 * --
 * A controllers for Games
 */

namespace GameScoreBundle\Controller;

use Doctrine\DBAL\Types\BooleanType;
use GameScoreBundle\Entity\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class GameController extends Controller
{

    public function gameCollectionAction()
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');
        $gameCollection = $em->findAll();

        if ($gameCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection de jeux.');
        }

        return $this->render(
            'GameScoreBundle:Game:gameCollection.html.twig',
            array(
                'gameCollection' => $gameCollection
            )
        );
    }


    public function readGameAction($game_id)
    {
        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');
        $game = $em->find($game_id);

        if ($game === null) {
            throw new NotFoundHttpException('Aucun jeu trouvé avec cet id : ' . $game_id);
        }

        return $this->render(
            'GameScoreBundle:Game:readGame.html.twig',
            array(
                'game' => $game
            )
        );
    }


    /* CRUD ****************************************************** */

    public function createGameAction(Request $request)
    {
        $game = new Game();
        $form = $this->createFormBuilder($game)
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('has_inverted_score', CheckboxType::class)
            ->add('is_collaborative', CheckboxType::class)
            ->add('is_extension', CheckboxType::class)
            ->add('img_url', TextType::class)
            ->add('year', TextType::class)
            ->add('save', SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($game);
                $em->flush();
            }
            $request
                ->getSession()
                ->getFlashBag()
                ->add('info', 'Le jeu a bien été créé.');
            return $this->redirectToRoute('game_score_view_game',
                array('game_id' => $game->getId()));
        }

        return $this->render('GameScoreBundle:Game:form.html.twig',
            array('form' => $form->createView()));
    }

    public function updateGameAction()
    {
    }

    public function deleteGameAction()
    {
    }


}