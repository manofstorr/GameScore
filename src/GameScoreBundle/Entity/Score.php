<?php

namespace GameScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Score
 *
 * @ORM\Table(name="score")
 * @ORM\Entity(repositoryClass="GameScoreBundle\Repository\ScoreRepository")
 */
class Score
{

    /**
     * @ORM\ManyToOne(targetEntity="GameScoreBundle\Entity\Play")
     * @ORM\JoinColumn(nullable=false)
     */
    private $play;

    /**
     * @ORM\ManyToOne(targetEntity="GameScoreBundle\Entity\Player")
     * @ORM\JoinColumn(nullable=false)
     */
    private $player;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="score", type="integer")
     */
    private $score;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set score
     *
     * @param integer $score
     *
     * @return Score
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return int
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set play
     *
     * @param \GameScoreBundle\Entity\Play $play
     *
     * @return Score
     */
    public function setPlay(\GameScoreBundle\Entity\Play $play)
    {
        $this->play = $play;

        return $this;
    }

    /**
     * Get play
     *
     * @return \GameScoreBundle\Entity\Play
     */
    public function getPlay()
    {
        return $this->play;
    }

    /**
     * Set player
     *
     * @param \GameScoreBundle\Entity\Player $player
     *
     * @return Score
     */
    public function setPlayer(\GameScoreBundle\Entity\Player $player)
    {
        $this->player = $player;

        return $this;
    }

    /**
     * Get player
     *
     * @return \GameScoreBundle\Entity\Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
}
