<?php

namespace GameScoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\DoctrineParamConverter;
use PDO;

/**
 * PlayRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayRepository extends EntityRepository
{

    public function getMostPlayedGamesByPlayer($player_id, $limit)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT COUNT(p.id) AS nbplays, g.name AS gamename
            FROM play p 
                INNER JOIN game g ON (g.id = p.game_id)
                INNER JOIN score s ON (s.play_id = p.id)
            WHERE s.player_id = :player_id
            GROUP BY p.game_id
            ORDER BY nbplays DESC
            LIMIT :limit"
        );
        $statement->bindValue('player_id', $player_id);
        $statement->bindValue('limit', (int)trim($limit), PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    // retun array of play id
    public function getPlaysByPlayer($player_id, $limit, $nbPerPage = 0)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT play.id
            FROM play
              INNER JOIN score scoreplayer ON (scoreplayer.play_id = play.id)
              INNER JOIN player ON (player.id = scoreplayer.player_id)
            WHERE scoreplayer.player_id = :player_id
            GROUP BY play.id
            ORDER BY play.date DESC LIMIT :limit, :offset"
        );
        $statement->bindValue('player_id', $player_id);
        $statement->bindValue('limit', (int)trim($limit), PDO::PARAM_INT);
        $statement->bindValue('offset', (int)trim($nbPerPage), PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getPlaysByGame($game_id, $limit, $nbPerPage)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT play.id
            FROM play
            WHERE game_id = :game_id
            ORDER BY play.date DESC LIMIT :limit, :offset"
        );
        $statement->bindValue('game_id', $game_id);
        $statement->bindValue('limit', (int)trim($limit), PDO::PARAM_INT);
        $statement->bindValue('offset', (int)trim($nbPerPage), PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

    public function getTrend(string $dateFrom, string $dateTo): array
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT 
                count(`id`) AS periodcount, 
                CONCAT(YEAR(`date`), ' ', LPAD(MONTH(`date`), 2, '0')) AS periodname 
            FROM `play`
            WHERE `date` BETWEEN :dateFrom AND :dateTo 
            GROUP BY periodname 
            ORDER BY periodname DESC"
        );
        $statement->bindValue('dateFrom', $dateFrom, PDO::PARAM_STR);
        $statement->bindValue('dateTo', $dateTo, PDO::PARAM_STR);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;

    }

    public function getMostPlayedGames($limit = 0, $nbPerPage = 25)
    {
        $em = $this->getEntityManager();
        $connection = $em->getConnection();
        $statement = $connection->prepare("
            SELECT COUNT(play.id) AS C, game.id, game.name
            FROM play
            INNER JOIN game ON (game.id = play.game_id) 
            GROUP BY play.game_id
            ORDER BY C DESC 
            LIMIT :limit, :offset"
        );
        $statement->bindValue('limit', (int)trim($limit), PDO::PARAM_INT);
        $statement->bindValue('offset', (int)trim($nbPerPage), PDO::PARAM_INT);
        $statement->execute();
        $results = $statement->fetchAll();

        return $results;
    }

}
