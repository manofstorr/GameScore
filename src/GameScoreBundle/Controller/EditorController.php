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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use GameScoreBundle\Form\Type\EditorType;
use GameScoreBundle\Entity\Editor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class EditorController extends Controller
{
    public function viewAction(Editor $editor)
    {

        // Author's games
        $games = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Game')
            ->findByEditor($editor->getId());
        $nbGames = count($games);

        return $this->render(
            'GameScoreBundle:Editor:view.html.twig',
            array(
                'editor'    => $editor,
                'nbGames'   => $nbGames,
                'games'     => $games
            )
        );
    }

    public function collectionAction($page = 1)
    {
        $nbPerPage = $this->container->getParameter('standard_number_of_elements_per_page');

        if ($page < 1) {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }

        $em = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Editor');

        $EditorCollection = $em->getEditors($page, $nbPerPage);
        $nbOfPages = ceil($EditorCollection->count() / $nbPerPage);

        if ($page > $nbOfPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        if ($EditorCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection d\'éditeurs.');
        }

        return $this->render(
            'GameScoreBundle:Editor:collection.html.twig',
            array(
                'editorCollection' => $EditorCollection,
                'nbOfPages' => $nbOfPages,
                'page' => $page,
            )
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function createAction(Request $request)
    {
        $editor = new Editor();
        $form = $this->createForm(EditorType::class, $editor);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($editor);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Editeur ajouté !');
                return $this->redirectToRoute('game_score_editor_view',
                    array('id' => $editor->getId()));

            }
        }
        return $this->render('GameScoreBundle:Editor:form.html.twig',
            array('form' => $form->createView()));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function updateAction(Request $request, Editor $editor)
    {
        $form = $this->createForm(EditorType::class, $editor);
        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($editor);
                $em->flush();

                $request
                    ->getSession()
                    ->getFlashBag()
                    ->add('info', 'Editeur mis à jour.');
                return $this->redirectToRoute('game_score_editor_view',
                    array('id' => $editor->getId()));
            }
        }
        return $this->render('GameScoreBundle:Editor:form.html.twig',
            array('form' => $form->createView()));

    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteEditorAction()
    {
    }

}

