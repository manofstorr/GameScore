<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 22/08/2017
 * Time: 15:55
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;
use GameScoreBundle\Entity\Document;

class DocumentService
{
    protected $em;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getDocuments($entitytype, $entityid)
    {
        return (
            $this
                ->em
                ->getRepository('GameScoreBundle:Document')
                ->getDocumentsX($entitytype, $entityid)
        );
    }
}