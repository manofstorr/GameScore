<?php

namespace GameScoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;

/**
 * PlayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayRepository extends EntityRepository
{

    // retun array of play id
    public function getPlaysByPlayer($player_id, $limit, $nbPerPage)
    {
        // constructing Limit clause
        if ($nbPerPage) {
            $limitClause = "LIMIT ".$limit.", ".$nbPerPage." ";
        } else {
            $limitClause = "LIMIT ".$limit." ";
        }
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT play.id
            FROM play
              INNER JOIN score scoreplayer ON (scoreplayer.play_id = play.id)
              INNER JOIN player ON (player.id = scoreplayer.player_id)
            WHERE scoreplayer.player_id = :player_id
            GROUP BY play.id
            ORDER BY play.date DESC "
            . $limitClause
        );
        $statement->bindValue('player_id', $player_id);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;

    }

    // played games for a player
    public function getPlayedGamesByPlayerX($player_id, $page, $nbPerPage)
    {
        // constructing Limit clause
        if ($nbPerPage) {
            $limitClause = "LIMIT ".$page.", ".$nbPerPage." ";
        } else {
            $limitClause = "LIMIT ".$page." ";
        }
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT game.id as gameid, game.name as gamename, play.id, play.date as playdate, 
            COUNT(scores.id) AS nbPlayers, scoreplayer.score as score, player.firstname,
            (SELECT MAX(score) 
             FROM score
             WHERE play_id = play.id) AS MAXScore
            FROM play
              INNER JOIN score scores ON (scores.play_id = play.id)
              INNER JOIN score scoreplayer ON (scoreplayer.play_id = play.id)
              INNER JOIN player ON (player.id = scoreplayer.player_id)
              INNER JOIN game ON (game.id = play.game_id)
            WHERE scoreplayer.player_id = :player_id
            GROUP BY play.id
            ORDER BY play.date DESC "
            . $limitClause
        );
        $statement->bindValue('player_id', $player_id);
        //$statement->bindValue('page', $page);
        //$statement->bindValue('nbperpage', $nbPerPage);
        $statement->execute();
        $results = $statement->fetchAll();
        return $results;
    }
}
