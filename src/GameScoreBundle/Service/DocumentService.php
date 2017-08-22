<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 22/08/2017
 * Time: 15:55
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;

class DocumentService
{
    protected $em;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getDocuments()
    {
        /*
        return count(
            $this
                ->em
                ->getRepository('GameScoreBundle:Game')
                ->findAll()
        );
        */
        return 'ok';
    }
}