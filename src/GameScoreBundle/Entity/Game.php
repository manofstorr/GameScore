<?php

namespace GameScoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\Length(min=3, minMessage="Le nom du jeu doit faire au moins {{ limit }} caractères")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank()
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
    private $collaborative = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_extension", type="boolean")
     */
    private $extension = false;

    /**
     * usually the url of trictrac card of this game
     * @var string
     *
     * @ORM\Column(name="main_card_url", type="string", length=255, nullable=true)
     */
    private $mainCardUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=4, nullable=true)
     * @Assert\Regex("#^[0-9]{4}$#", message="Merci d'utiliser une chaïne de 4 chiffres .")
     */
    private $year;

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
    public function setCollaborative($isCollaborative)
    {
        $this->collaborative = $isCollaborative;

        return $this;
    }

    /**
     * Get isCollaborative
     *
     * @return bool
     */
    public function getCollaborative()
    {
        return $this->collaborative;
    }

    /**
     * Set isExtension
     *
     * @param boolean $isExtension
     *
     * @return Game
     */
    public function setExtension($isExtension)
    {
        $this->extension = $isExtension;

        return $this;
    }

    /**
     * Get isExtension
     *
     * @return bool
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set imgUrl
     *
     * @param string $mainCardUrl
     *
     * @return Game
     */
    public function setMainCardUrl($mainCardUrl)
    {
        $this->mainCardUrl = $mainCardUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getMainCardUrl()
    {
        return $this->mainCardUrl;
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
        $this->author = new ArrayCollection();
    }

    /**
     * Add author
     *
     * @param \GameScoreBundle\Entity\Author $author
     *
     * @return Game
     */
    public function addAuthor(Author $author)
    {
        $this->author[] = $author;

        return $this;
    }

    /**
     * Remove author
     *
     * @param \GameScoreBundle\Entity\Author $author
     */
    public function removeAuthor(Author $author)
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


