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

    private $gameRepository;

    private function setGameRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->gameRepository = $em->getRepository('GameScoreBundle:Game');
    }

    public function gameCollectionAction()
    {
        $this->setGameRepository();
        $gameCollection = $this->gameRepository->findAll();

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
        $this->setGameRepository();
        $game = $this->gameRepository->find($game_id);

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
        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'Create... more');
            return $this->redirectToRoute('game_score_view_game', array('game_id' => 27));
        }
        $game = new Game();

        $formBuilder = $this->createFormBuilder($game);
        $formBuilder
            ->add('name', TextType::class)
            ->add('description', TextareaType::class)
            ->add('has_inverted_score', CheckboxType::class)
            ->add('is_collaborative', CheckboxType::class)
            ->add('is_extension', CheckboxType::class)
            ->add('img_url', TextType::class)
            ->add('year', TextType::class);
        $form = $formBuilder->getForm();

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