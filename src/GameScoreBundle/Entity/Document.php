<?php
/**
 * Created by PhpStorm.
 * User: d.baudry
 * Date: 21/08/2017
 * Time: 16:57
 */

namespace GameScoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Table(name="document")
 * @ORM\Entity(repositoryClass="GameScoreBundle\Repository\DocumentRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Document
{

    private $temp;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    public $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $entitytype;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    public $entityid;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $filename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $filepath;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getEntitytype()
    {
        return $this->entitytype;
    }

    /**
     * @param mixed $entitytype
     */
    public function setEntitytype($entitytype)
    {
        $this->entitytype = $entitytype;
    }

    /**
     * @return mixed
     */
    public function getEntityid()
    {
        return $this->entityid;
    }

    /**
     * @param mixed $entitytid
     */
    public function setEntityid($entityid)
    {
        $this->entityid = $entityid;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->filename)) {
            // store the old name to delete after the update
            $this->temp = $this->filename;
            $this->filename = null;
        } else {
            $this->filename = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->filepath = $this->getUploadDir();
            $this->filename = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->filename);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadRootDir() . '/' . $this->filename;
    }

    public function getWebPath()
    {
        return null === $this->filename
            ? null
            : $this->getUploadDir() . '/' . $this->filename;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        $dir = 'uploads/documents';
        if ($this->getEntitytype()) {
            $dir .= '/' . $this->getEntitytype() . '/' . $this->getEntityid();
        } else {
            $dir .= '/common';
        }

        return $dir;
    }

}
