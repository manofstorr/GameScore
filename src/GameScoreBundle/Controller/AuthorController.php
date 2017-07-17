<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 24/06/2017
 * Time: 16:22
 */

namespace GameScoreBundle\Controller;

use GameScoreBundle\Entity\Author;
use GameScoreBundle\Form\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class AuthorController extends Controller
{
    private $authorRepository;

    private function setAuthorRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->authorRepository = $em->getRepository('GameScoreBundle:Author');
    }

    public function collectionAction()
    {
        $this->setAuthorRepository();
        $authorCollection = $this->authorRepository->findAll();

        if ($authorCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection d\'auteurs.');
        }

        return $this->render(
            'GameScoreBundle:Author:collection.html.twig',
            array(
                'authorCollection' => $authorCollection
            )
        );
    }


    public function viewAction($author_id)
    {
        $this->setAuthorRepository();
        $author = $this->authorRepository->find($author_id);

        if ($author === null) {
            throw new NotFoundHttpException('Aucun auteur trouvé avec cet id : ' . $author_id);
        }

        return $this->render(
            'GameScoreBundle:Author:view.html.twig',
            array(
                'author' => $author
            )
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $author = new Author();
        $form = $this->createForm(AuthorType::class, $author);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($author);
                $em->flush();
                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Auteur ajouté !');
                return $this->redirectToRoute('game_score_author_view',
                    array('author_id' => $author->getId()));
            }
        }

        return $this->render('GameScoreBundle:Author:form.html.twig',
            array('form' => $form->createView()));

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, int $author_id)
    {
        $author = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Author')
            ->find($author_id);

        $form = $this->createForm(AuthorType::class, $author);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($author);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Auteur mis à jour.');
                return $this->redirectToRoute('game_score_author_view',
                    array('author_id' => $author->getId()));
            }
        }
        return $this->render('GameScoreBundle:Author:form.html.twig',
            array('form' => $form->createView()));

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteAuthorAction()
    {
    }

}