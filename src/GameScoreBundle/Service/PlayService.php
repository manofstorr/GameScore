<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 23/07/2017
 * Time: 17:38
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class PlayService
{
    protected $em;
    private $container;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
    }

    public function getPlayedGamesByPlayer($player_id, $limit, $offset)
    {
        $playedGames = $this
            ->em
            ->getRepository('GameScoreBundle:Play')
            ->getPlaysByPlayer($player_id, $limit, null);

        foreach ($playedGames as $key => $playedGame) {
            echo $key;
            var_dump($playedGame);
            // retrieve scores

        }
        return $playedGames;


    }

    private function getScoresByPlay(){

    }

}