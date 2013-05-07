<?php
// src/NNWelcome/NticBundle/Entity/ObjectType.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="object_type")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\ObjectTypeRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\NticBundle\Entity\ObjectTypeTranslation")
 */
class ObjectType {

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
    protected $title; 
    
     /**
     * @ORM\ManyToOne(
     *   targetEntity="NNWelcome\NticBundle\Entity\EntityType", 
     *   inversedBy="object")
     * @ORM\JoinColumn(
     *   name="entity_type_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $entity_type;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="NNWelcome\NticBundle\Entity\Hotel", 
     *   mappedBy="object", 
     *   cascade={"persist", "remove"})
    */
    protected $hotel;
    
    protected $action;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->hotel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set title
     *
     * @param string $title
     * @return ObjectType
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
     * Set entity_type
     *
     * @param \NNWelcome\NticBundle\Entity\EntityType $entityType
     * @return ObjectType
     */
    public function setEntityType(\NNWelcome\NticBundle\Entity\EntityType $entityType = null)
    {
        $this->entity_type = $entityType;
    
        return $this;
    }

    /**
     * Get entity_type
     *
     * @return \NNWelcome\NticBundle\Entity\EntityType 
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * Add hotel
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     * @return ObjectType
     */
    public function addHotel(\NNWelcome\NticBundle\Entity\Hotel $hotel)
    {
        $this->hotel[] = $hotel;
    
        return $this;
    }

    /**
     * Remove hotel
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     */
    public function removeHotel(\NNWelcome\NticBundle\Entity\Hotel $hotel)
    {
        $this->hotel->removeElement($hotel);
    }

    /**
     * Get hotel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getHotel()
    {
        return $this->hotel;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function __toString() {
        return $this->getTitle();
    }
}