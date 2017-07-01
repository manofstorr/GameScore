<?php

namespace GameScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Play
 *
 * @ORM\Table(name="play")
 * @ORM\Entity(repositoryClass="GameScoreBundle\Repository\PlayRepository")
 */
class Play
{

    /**
     * @ORM\ManyToOne(targetEntity="GameScoreBundle\Entity\Game")
     * @ORM\JoinColumn(nullable=false)
     */
    private $game;


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="location", type="string", length=255, nullable=true)
     */
    private $location;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Play
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Play
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return Play
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set game
     *
     * @param \GameScoreBundle\Entity\Game $game
     *
     * @return Play
     */
    public function setGame(\GameScoreBundle\Entity\Game $game)
    {
        $this->game = $game;

        return $this;
    }

    /**
     * Get game
     *
     * @return \GameScoreBundle\Entity\Game
     */
    public function getGame()
    {
        return $this->game;
    }
}
