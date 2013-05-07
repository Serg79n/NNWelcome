<?php
// src/NNWelcome/NticBundle/Entity/EntityType.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="entity_type")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\EntityTypeRepository")
 */
class EntityType {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title; 
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="NNWelcome\NticBundle\Entity\ObjectType", 
     *   mappedBy="entity_type", 
     *   cascade={"persist", "remove"})
    */
    protected $object; 
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->object = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return EntityType
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
     * Add object
     *
     * @param \NNWelcome\NticBundle\Entity\ObjectType $object
     * @return EntityType
     */
    public function addObject(\NNWelcome\NticBundle\Entity\ObjectType $object)
    {
        $this->object[] = $object;
    
        return $this;
    }

    /**
     * Remove object
     *
     * @param \NNWelcome\NticBundle\Entity\ObjectType $object
     */
    public function removeObject(\NNWelcome\NticBundle\Entity\ObjectType $object)
    {
        $this->object->removeElement($object);
    }

    /**
     * Get object
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObject()
    {
        return $this->object;
    }
    
    public function __toString() {
        return $this->getTitle();
    }
}