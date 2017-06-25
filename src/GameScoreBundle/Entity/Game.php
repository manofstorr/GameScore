<?php

namespace GameScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use GameScoreBundle\Entity;

/**
 * Game
 *
 * @ORM\Table(name="game")
 * @ORM\Entity(repositoryClass="GameScoreBundle\Repository\GameRepository")
 */
class Game
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="GameScoreBundle\Entity\Author")
     * @ORM\JoinColumn(nullable=true)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="GameScoreBundle\Entity\Editor")
     * @ORM\JoinColumn(nullable=true)
     */
    private $editor;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_inverted_score", type="boolean")
     */
    private $hasInvertedScore = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_collaborative", type="boolean")
     */
    private $isCollaborative = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_extension", type="boolean")
     */
    private $isExtension = false;

    /**
     * @var string
     *
     * @ORM\Column(name="img_url", type="string", length=255, nullable=true)
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=4, nullable=true)
     */
    private $year = '0000';

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
     * Set name
     *
     * @param string $name
     *
     * @return Game
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Game
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
     * Set hasInvertedScore
     *
     * @param boolean $hasInvertedScore
     *
     * @return Game
     */
    public function setHasInvertedScore($hasInvertedScore)
    {
        $this->hasInvertedScore = $hasInvertedScore;

        return $this;
    }

    /**
     * Get hasInvertedScore
     *
     * @return bool
     */
    public function getHasInvertedScore()
    {
        return $this->hasInvertedScore;
    }

    /**
     * Set isCollaborative
     *
     * @param boolean $isCollaborative
     *
     * @return Game
     */
    public function setIsCollaborative($isCollaborative)
    {
        $this->isCollaborative = $isCollaborative;

        return $this;
    }

    /**
     * Get isCollaborative
     *
     * @return bool
     */
    public function getIsCollaborative()
    {
        return $this->isCollaborative;
    }

    /**
     * Set isExtension
     *
     * @param boolean $isExtension
     *
     * @return Game
     */
    public function setIsExtension($isExtension)
    {
        $this->isExtension = $isExtension;

        return $this;
    }

    /**
     * Get isExtension
     *
     * @return bool
     */
    public function getIsExtension()
    {
        return $this->isExtension;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Game
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * @return string
     */
    public function getYear(): string
    {
        if($this->year === null){
            $this->setYear('0000');
        }
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear(string $year)
    {
        $this->year = $year;
    }
    

    /**
     * Set editor
     *
     * @param \GameScoreBundle\Entity\Editor $editor
     *
     * @return Game
     */
    public function setEditor($editor = null)
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * Get editor
     *
     * @return \GameScoreBundle\Entity\Editor
     */
    public function getEditor()
    {
        return $this->editor;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->author = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add author
     *
     * @param \GameScoreBundle\Entity\Author $author
     *
     * @return Game
     */
    public function addAuthor(\GameScoreBundle\Entity\Author $author)
    {
        $this->author[] = $author;

        return $this;
    }

    /**
     * Remove author
     *
     * @param \GameScoreBundle\Entity\Author $author
     */
    public function removeAuthor(\GameScoreBundle\Entity\Author $author)
    {
        $this->author->removeElement($author);
    }

    /**
     * Get author
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAuthor()
    {
        return $this->author;
    }
}
