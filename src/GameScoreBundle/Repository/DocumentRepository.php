<?php

namespace GameScoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * DocumentRepository
 *
 */
class DocumentRepository extends EntityRepository
{
    public function getDocumentsX($entitytype, $entityid)
    {
        $query = $this->createQueryBuilder('d')
            ->where('d.entitytype = :entitytype')
            ->andwhere('d.entityid = :entityid')
            ->setParameter('entitytype', $entitytype)
            ->setParameter('entityid', $entityid)
            ->orderBy('d.id', 'ASC')
            ->getQuery();
        return $query->getResult();
    }
}