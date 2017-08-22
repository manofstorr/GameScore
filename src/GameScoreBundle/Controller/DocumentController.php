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

                return $this->redirect($this->generateUrl('game_score_homepage'));
            }
        }

        return $this->render('GameScoreBundle:Document:form.html.twig',
            array('form' => $form->createView()));
    }

    private function isValidEntityForUpload(string $entitytype, int $entityid)
    {
        $acceptedEntitiesNames = array('game', 'play', 'player', 'editor', 'author', 'common');
        if (in_array($entitytype, $acceptedEntitiesNames) and ($entityid)) {
            return true;
        }
        return false;
    }

    private function setUploadName($entitytype, $entityid)
    {
        $acceptedEntitiesNames = array('game', 'play', 'player', 'editor', 'author');
        if (in_array($entitytype, $acceptedEntitiesNames) and ($entityid)) {
            return $entitytype . '-' . $entityid;
        }
        return '';
    }
}