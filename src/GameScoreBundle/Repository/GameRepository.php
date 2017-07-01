<?php

namespace GameScoreBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends \Doctrine\ORM\EntityRepository
{
    public function getGames($page)
    {
        $query = $this->createQueryBuilder('g')
            ->where('g.name LIKE :word')
            ->setParameter('word', $page.'%');
        return new Paginator($query, true);
    }
}
