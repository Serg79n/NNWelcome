<?php
// src/NNWelcome/NticBundle/Entity/HotelFile.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="hotel_file")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\HotelFileRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\NticBundle\Entity\HotelFileTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class HotelFile {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $sort;
    
    /**
     * @Assert\File(maxSize="6000000")
     */
    protected $file;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="HotelFileTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Hotel", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="hotel_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $hotel;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="NNWelcome\FileCategoryBundle\Entity\FileCategory", 
     *   inversedBy="files")
     * @ORM\JoinColumn(
     *   name="file_category_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $file_category;
    
    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        return null === $this->path ? null : '/' . $this->getUploadDir() . '/' . $this->path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/documents';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->file) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->file->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->file) {
            return;
        }

        $this->file->move($this->getUploadRootDir(), $this->path);
        unset($this->file);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($file = $this->getAbsolutePath()) {
            if(file_exists($file))
                unlink($file);
        }
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set sort
     *
     * @param integer $sort
     * @return HotelFile
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set file
     *
     * @param string $file
     * @return HotelFile
     */
    public function setFile($file)
    {
        $this->file = $file;
    
        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return HotelFile
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return HotelFile
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
     * Add translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelFileTranslation $translations
     * @return HotelFile
     */
    public function addTranslation(\NNWelcome\NticBundle\Entity\HotelFileTranslation $t)
    {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelFileTranslation $translations
     */
    public function removeTranslation(\NNWelcome\NticBundle\Entity\HotelFileTranslation $translations)
    {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations()
    {
        return $this->translations;
    }

    /**
     * Set hotel
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     * @return HotelFile
     */
    public function setHotel(\NNWelcome\NticBundle\Entity\Hotel $hotel = null)
    {
        $this->hotel = $hotel;
    
        return $this;
    }

    /**
     * Get hotel
     *
     * @return \NNWelcome\NticBundle\Entity\Hotel 
     */
    public function getHotel()
    {
        return $this->hotel;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return HotelFile
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set file_category
     *
     * @param \NNWelcome\FileCategoryBundle\Entity\FileCategory $fileCategory
     * @return HotelFile
     */
    public function setFileCategory(\NNWelcome\FileCategoryBundle\Entity\FileCategory $fileCategory = null)
    {
        $this->file_category = $fileCategory;
    
        return $this;
    }

    /**
     * Get file_category
     *
     * @return \NNWelcome\FileCategoryBundle\Entity\FileCategory 
     */
    public function getFileCategory()
    {
        return $this->file_category;
    }
}