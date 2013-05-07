<?php
// src/NNWelcome/NticBundle/Entity/HotelMeta.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="hotel_meta")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\HotelMetaRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\NticBundle\Entity\HotelMetaTranslation")
 */
class HotelMeta {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_title; 
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $meta_keywords;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $meta_description;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="HotelMetaTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;
    
    /**
     * @ORM\OneToOne(targetEntity="Hotel", inversedBy="meta")
     * @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     */
    protected $hotel;
    
    /**
     * @ORM\Column(name="hotel_id", type="integer")
     */
    protected $hotelt_id;
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
     * Set meta_title
     *
     * @param string $metaTitle
     * @return HotelMeta
     */
    public function setMetaTitle($metaTitle)
    {
        $this->meta_title = $metaTitle;
    
        return $this;
    }

    /**
     * Get meta_title
     *
     * @return string 
     */
    public function getMetaTitle()
    {
        return $this->meta_title;
    }

    /**
     * Set meta_keywords
     *
     * @param string $metaKeywords
     * @return HotelMeta
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->meta_keywords = $metaKeywords;
    
        return $this;
    }

    /**
     * Get meta_keywords
     *
     * @return string 
     */
    public function getMetaKeywords()
    {
        return $this->meta_keywords;
    }

    /**
     * Set meta_description
     *
     * @param string $metaDescription
     * @return HotelMeta
     */
    public function setMetaDescription($metaDescription)
    {
        $this->meta_description = $metaDescription;
    
        return $this;
    }

    /**
     * Get meta_description
     *
     * @return string 
     */
    public function getMetaDescription()
    {
        return $this->meta_description;
    }

    /**
     * Add translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelMetaTranslation $translations
     * @return HotelMeta
     */
    public function addTranslation(\NNWelcome\NticBundle\Entity\HotelMetaTranslation $translations)
    {
        if (!$this->translations->contains($translations)) {
            $this->translations[] = $translations;
            $translations->setObject($this);
        }
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelMetaTranslation $translations
     */
    public function removeTranslation(\NNWelcome\NticBundle\Entity\HotelMetaTranslation $translations)
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
     * Set project
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     * @return HotelMeta
     */
    public function setHotel(\NNWelcome\NticBundle\Entity\Hotel $hotel = null)
    {
        $this->project = $hotel;
    
        return $this;
    }

    /**
     * Get project
     *
     * @return \NNWelcome\NticBundle\Entity\Hotel 
     */
    public function getHotel()
    {
        return $this->project;
    }

    /**
     * Set hotel_id
     *
     * @param integer $hotelId
     * @return HotelMeta
     */
    public function setHotelId($hotelId)
    {
        $this->hotel_id = $hotelId;
    
        return $this;
    }

    /**
     * Get hotel_id
     *
     * @return integer 
     */
    public function getHotelId()
    {
        return $this->hotel_id;
    }

    /**
     * Set hotelt_id
     *
     * @param integer $hoteltId
     * @return HotelMeta
     */
    public function setHoteltId($hoteltId)
    {
        $this->hotelt_id = $hoteltId;
    
        return $this;
    }

    /**
     * Get hotelt_id
     *
     * @return integer 
     */
    public function getHoteltId()
    {
        return $this->hotelt_id;
    }
}