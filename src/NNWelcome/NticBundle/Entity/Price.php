<?php
//src/NNWelcome/NticBundle/Entity/Price.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="price")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\PriceRepository")
 */
class Price {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $from;
    
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $to;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="NNWelcome\NticBundle\Entity\Hotel", 
     *   mappedBy="price", 
     *   cascade={"persist", "remove"})
    */
    protected $hotel; 
    
    protected $action;
    
    protected $range;
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
     * Set from
     *
     * @param string $from
     * @return Price
     */
    public function setFrom($from)
    {
        $this->from = $from;
    
        return $this;
    }

    /**
     * Get from
     *
     * @return string 
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set to
     *
     * @param string $to
     * @return Price
     */
    public function setTo($to)
    {
        $this->to = $to;
    
        return $this;
    }

    /**
     * Get to
     *
     * @return string 
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Add hotel
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     * @return Price
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
    
    public function getRange(){
        return $this->getFrom().' - '.$this->getTo();
    }
}