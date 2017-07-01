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
use GameScoreBundle\Form\EditorType;
use GameScoreBundle\Entity\Editor;
//use Doctrine\ORM\Tools\Pagination\Paginator;


class EditorController extends Controller
{
    private $EditorRepository;

    private function setEditorRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $this->EditorRepository = $em->getRepository('GameScoreBundle:Editor');
    }

    public function EditorCollectionAction($page=1)
    {
        $this->setEditorRepository();
        $nbPerPage = $this->container->getParameter('standard_number_of_elements_per_page');

        if ($page < 1) {
            throw $this->createNotFoundException("La page demandée (" . $page . ") n'existe pas.");
        }

        $EditorCollection = $this->EditorRepository->getEditors($page, $nbPerPage);
        // todo : put this in a service ?
        $nbOfPages = ceil($EditorCollection->count() / $nbPerPage);

        if ($page > $nbOfPages) {
            throw $this->createNotFoundException("La page " . $page . " n'existe pas.");
        }

        if ($EditorCollection === null) {
            throw new NotFoundHttpException('Impossible de charger la collection d\'éditeurs.');
        }

        return $this->render(
            'GameScoreBundle:Editor:editorCollection.html.twig',
            array(
                'editorCollection' => $EditorCollection,
                'nbOfPages'     => $nbOfPages,
                'page'        => $page,
            )
        );
    }


    public function readEditorAction($editor_id)
    {
        $this->setEditorRepository();
        $editor = $this->EditorRepository->find($editor_id);

        if ($editor === null) {
            throw new NotFoundHttpException('Aucun éditeur trouvé avec cet id : ' . $editor_id);
        }

        return $this->render(
            'GameScoreBundle:editor:readEditor.html.twig',
            array(
                'editor' => $editor
            )
        );
    }


    /* CRUD ****************************************************** */

    public function createEditorAction(Request $request)
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
                return $this->redirectToRoute('game_score_view_editor',
                    array('editor_id' => $editor->getId()));

            }
        }

        return $this->render('GameScoreBundle:Editor:form.html.twig',
            array('form' => $form->createView()));

    }

    public function updateEditorAction(Request $request, int $editor_id)
    {
        $editor = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('GameScoreBundle:Editor')
            ->find($editor_id);

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
                return $this->redirectToRoute('game_score_view_editor',
                    array('editor_id' => $editor->getId()));
            }

        }

        return $this->render('GameScoreBundle:Editor:form.html.twig',
            array('form' => $form->createView()));

    }

    public function deleteEditorAction()
    {
    }

}