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
    public function uploadAction(Request $request)
    {
        $document = new Document();
        $form = $this->createFormBuilder($document)
            ->add('name')
            ->add('file')
            ->getForm()
        ;

        if ($request->isMethod('POST')) {
            var_dump($_POST);
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
}