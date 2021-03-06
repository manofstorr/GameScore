<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 24/06/2017
 * Time: 16:22
 */

namespace GameScoreBundle\Controller;

use GameScoreBundle\Entity\Author;
use GameScoreBundle\Form\Type\AuthorType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use GameScoreBundle\Repository\GameRepository;

class AuthorController extends Controller
{

    public function collectionAction($page)
    {
        $authors = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Author')
            ->getByPage($page);

        if ($authors === null) {
            throw new NotFoundHttpException('Impossible de charger la collection d\'auteurs.');
        }

        $alphabeticalIndex = $this
            ->container->get('alphabetical_pagination')
            ->getAlphabeticalPagination('author');

        return $this->render(
            'GameScoreBundle:Author:collection.html.twig',
            array(
                'authors' => $authors,
                'page' => $page,
                'alphapageArray' => $alphabeticalIndex
            )
        );
    }

    public function viewAction(Author $author)
    {
        if ($author === null) {
            throw new NotFoundHttpException('Aucun auteur trouvé');
        }

        // Author's games
        $games = [];
        $gamesRepository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game');
        $gamesIds = $gamesRepository->findByAuthor($author->getId());
        foreach ($gamesIds as $gameId) {
            $games[] = $gamesRepository->find($gameId);
        }
        $nbGames = count($games);

        return $this->render(
            'GameScoreBundle:Author:view.html.twig',
            array(
                'author' => $author,
                'nbGames'   => $nbGames,
                'games'     => $games
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
                    array('id' => $author->getId()));
            }
        }

        return $this->render('GameScoreBundle:Author:form.html.twig',
            array('form' => $form->createView()));

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, Author $author)
    {
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
                    array('id' => $author->getId()));
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
