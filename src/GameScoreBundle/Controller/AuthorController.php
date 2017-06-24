<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 24/06/2017
 * Time: 16:22
 */

namespace GameScoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


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

    public function createAuthorCardAction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $session = $request->getSession();
            $session->getFlashBag()->add('info', 'Create... more');
            return $this->redirectToRoute('game_score_view_author_card', array('author_id' => 27));
        }
        return $this->render('GameScoreBundle:Author:Create.html.twig');
    }

    public function updateAuthorAction()
    {
    }

    public function deleteAuthorAction()
    {
    }

}