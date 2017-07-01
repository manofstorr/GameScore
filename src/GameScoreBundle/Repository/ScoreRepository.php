<?php

namespace GameScoreBundle\Repository;

use \Doctrine\ORM\EntityRepository;

/**
 * ScoreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScoreRepository extends EntityRepository
{
    public function getScoresWithPlaysByGame($game)
    {
        $query = $this->createQueryBuilder('score')
            ->innerJoin('score.play', 'play')
            ->addSelect('play')
            ->innerJoin('play.game', 'game')
            ->where('play.game = :game')
            ->setParameter('game', $game)
            ->orderBy('play.date', 'ASC')
            ->getQuery();
        return $query->getResult();
    }
}
