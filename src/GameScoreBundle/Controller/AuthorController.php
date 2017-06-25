<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 24/06/2017
 * Time: 16:22
 */

namespace GameScoreBundle\Controller;

use GameScoreBundle\Entity\Author;
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


class AuthorController extends Controller
{
    private $authorRepository;

    private function setAuthorRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->authorRepository = $em->getRepository('GameScoreBundle:Author');
    }

    public function authorCollectionAction()
    {
        $this->setAuthorRepository();
        $authorCollection = $this->authorRepository->findAll();

        if ($authorCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection d\'auteurs.');
        }

        return $this->render(
            'GameScoreBundle:Author:authorCollection.html.twig',
            array(
                'authorCollection' => $authorCollection
            )
        );
    }


    public function readAuthorCardAction($author_id)
    {
        $this->setAuthorRepository();
        $author = $this->authorRepository->find($author_id);

        if ($author === null) {
            throw new NotFoundHttpException('Aucun auteur trouvé avec cet id : ' . $game_id);
        }

        return $this->render(
            'GameScoreBundle:Author:readAuthorCard.html.twig',
            array(
                'author' => $author
            )
        );
    }


    /* CRUD ****************************************************** */

    public function createAuthorAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'Create... more');
            return $this->redirectToRoute('game_score_view_author_card', array('author_id' => 27));
        }

        $author = new Author();

        $formBuilder = $this->createFormBuilder($author);
        $formBuilder
            ->add('firstname', TextType::class)
            ->add('lastname', TextType::class)
            ->add('save', SubmitType::class)
        ;
        $form = $formBuilder->getForm();
        //$form = $formBuilder->getForm();
        return $this->render('GameScoreBundle:Author:form.html.twig',
            array('form' => $form->CreateView()));
    }

    public function updateAuthorAction()
    {
    }

    public function deleteAuthorAction()
    {
    }

}