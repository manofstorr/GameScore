<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 21/08/2017
 * Time: 16:59
 */

namespace GameScoreBundle\Controller;

use GameScoreBundle\Entity\Document;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DocumentController extends Controller
{
    public function uploadAction(Request $request, $entitytype, $entityid)
    {
        if (!$this->isValidEntityForUpload($entitytype, $entityid)) {
            return false;
        }

        $document = new Document();
        // hydratting from route parameters
        $document->setEntitytype($entitytype);
        $document->setEntityid($entityid);
        $document->setName($this->setUploadName($entitytype, $entityid));
        $form = $this->createFormBuilder($document)
            ->add('name')
            ->add('entitytype')
            ->add('entityid')
            ->add('file')
            ->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->persist($document);
                $em->flush();

                // route out depends on entity
                $available_entities_names = $this->getParameter('available_entities_names');
                if (in_array($entitytype, $available_entities_names)) {
                    return $this->redirect($this->generateUrl(
                        'game_score_' . $entitytype . '_view',
                        array('id' => $entityid))
                    );
                }
                return $this->redirect($this->generateUrl('game_score_homepage'));
            }
        }
        return $this->render('GameScoreBundle:Document:form.html.twig',
            array('form' => $form->createView()));
    }

    private function isValidEntityForUpload(string $entitytype, int $entityid)
    {
        $available_entities_names = $this->getParameter('available_entities_names');
        $document_casual_name = $this->getParameter('document_casual_name');
        array_push($available_entities_names, $document_casual_name);
        if (in_array($entitytype, $available_entities_names) and ($entityid)) {
            return true;
        }
        return false;
    }

    private function getReturnPath(string $entitytype, int $entityid)
    {
        $available_entities_names = $this->getParameter('available_entities_names');
        if (in_array($entitytype, $available_entities_names) and ($entityid)) {
            return "'gamescore_bundle_' . $entitytype . '_view' { 'id':game.editor.id }";
        }
        return false;
    }

    private function setUploadName($entitytype, $entityid)
    {
        $available_entities_names = $this->getParameter('available_entities_names');
        if (in_array($entitytype, $available_entities_names) and ($entityid)) {
            return $entitytype . '-' . $entityid;
        }
        return '';
    }
}