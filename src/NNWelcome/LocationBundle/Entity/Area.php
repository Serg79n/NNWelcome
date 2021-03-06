<?php
// src/NNWelcome/LocationBundle/Entity/Area.php
namespace NNWelcome\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="area")
 * @ORM\Entity(repositoryClass="NNWelcome\LocationBundle\Entity\Repository\AreaRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\LocationBundle\Entity\AreaTranslation")
 */
class Area {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="AreaTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="City", 
     *   inversedBy="areas")
     * @ORM\JoinColumn(
     *   name="city_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $city;
    
    protected $action;
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
     * Set title
     *
     * @param string $title
     * @return Area
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Area
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;
    
        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean 
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Add translations
     *
     * @param \NNWelcome\LocationBundle\Entity\AreaTranslation $translations
     * @return Area
     */
    public function addTranslation(\NNWelcome\LocationBundle\Entity\AreaTranslation $t)
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
     * @param \NNWelcome\LocationBundle\Entity\AreaTranslation $translations
     */
    public function removeTranslation(\NNWelcome\LocationBundle\Entity\AreaTranslation $translations)
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
     * Set city
     *
     * @param \NNWelcome\LocationBundle\Entity\City $city
     * @return Area
     */
    public function setCity(\NNWelcome\LocationBundle\Entity\City $city = null)
    {
        $this->city = $city;
    
        return $this;
    }

    /**
     * Get city
     *
     * @return \NNWelcome\LocationBundle\Entity\City 
     */
    public function getCity()
    {
        return $this->city;
    }
    
    public function set($field, $value){
        $t = explode('_', $field);
        for($i = 0; $i <= count($t)-1; $i++){
            $t[$i] = ucfirst($t[$i]); 
        }
        call_user_func(array($this, 'set'.implode('', $t)), $value);
        return $this;
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