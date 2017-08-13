<?php
/**
 * Created by PhpStorm.
 * User: Actif
 * Date: 23/07/2017
 * Time: 17:38
 */

namespace GameScoreBundle\Service;

use Doctrine\ORM\EntityManager;
use GameScoreBundle\Entity\Game;
use Symfony\Component\DependencyInjection\Container;

class PlayService
{
    protected $em;
    private $container;
    private $playsIds;

    // We need to inject this variables later.
    public function __construct(EntityManager $entityManager, Container $container)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->playsIds = array();
    }

    public function getPlayedGames($ByWhat, $ByValue, $limit, $offset)
    {
        // 1. determine how to retrieve play ids
        switch ($ByWhat) {
            case 'player_id' :
                $this->setPlaysIds($this->getPlayedGamesByPlayer($ByValue, $limit, $offset));
                break;
            case 'game_id' :
                $this->setPlaysIds($this->getPlayedGamesByGame($ByValue, $limit, $offset));
                break;
            case 'single_play_id' :
                $this->setPlaysIds(array(0 => $ByValue));
                break;

        }
        if (count($this->getPlaysIds()) == 0) {
            return false;
        }
        // 2. Find all data
        $plays = array();
        foreach ($this->getPlaysIds() as $playKey => $playedGameId) {
            // retrieve play data
            $play = $this->getPlayById($playedGameId);
            $plays[$playKey]['playid'] = $play[0]->getId();
            $plays[$playKey]['date'] = $play[0]->getDate();
            $plays[$playKey]['description'] = $play[0]->getDescription();
            $plays[$playKey]['location'] = $play[0]->getLocation();
            $plays[$playKey]['game']['id'] = $play[0]->getGame()->getId();
            $plays[$playKey]['game']['name'] = $play[0]->getGame()->getName();
            // loop retrieve scores
            $scores = $this->getScoresByPlayId($playedGameId);
            foreach ($scores as $playerKey => $score) {
                //var_dump($score);
                $plays[$playKey]['player'][$playerKey]['scoreid'] = $score->getId();
                $plays[$playKey]['player'][$playerKey]['id'] = $score->getPlayer()->getId();
                $plays[$playKey]['player'][$playerKey]['firstname'] = $score->getPlayer()->getFirstname();
                $plays[$playKey]['player'][$playerKey]['lastname'] = $score->getPlayer()->getLastname();
                $plays[$playKey]['player'][$playerKey]['score'] = $score->getScore();
            }
            $plays[$playKey]['nbPlayers'] = count($scores);
        }
        return $plays;
    }

    /*
     * Retrieving array of play ids methods :
     */
    private function getPlayedGamesByPlayer($player_id, $limit, $offset)
    {
        $playedGameIds = $this
            ->em
            ->getRepository('GameScoreBundle:Play')
            ->getPlaysByPlayer($player_id, $limit, $offset);
        return $playedGameIds;
    }

    private function getPlayedGamesByGame($game_id, $limit, $offset)
    {
        $playedGameIds = $this
            ->em
            ->getRepository('GameScoreBundle:Play')
            ->getPlaysByGame($game_id, $limit, $offset);
        return $playedGameIds;
    }

    private function getPlayById($id)
    {
        return $this
            ->em
            ->getRepository('GameScoreBundle:Play')
            ->findById($id);
    }

    private function getScoresByPlayId($id)
    {
        return $this
            ->em
            ->getRepository('GameScoreBundle:Score')
            ->findBy(
                array('play' => $id),
                array('score' => 'DESC')
            );
    }

    /*
     * Getters and setters
     */

    /**
     * @return array
     */
    public function getPlaysIds(): array
    {
        return $this->playsIds;
    }

    /**
     * @param array $playsIds
     */
    public function setPlaysIds(array $playsIds)
    {
        $this->playsIds = $playsIds;
    }

    public function getBestScoresByGame(Game $game)
    {
        $limit = $this->container->getParameter('number_of_item_to_show_on_top_scores');
        // check the order of scores for this game
        $invertedScore = $game->getHasInvertedScore();
        return $this
            ->em
            ->getRepository('GameScoreBundle:Score')
            ->getTopScoresByGame($game, $limit, $invertedScore);
    }


}