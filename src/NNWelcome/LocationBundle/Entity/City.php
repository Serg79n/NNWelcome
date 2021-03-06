<?php
// src/NNWelcome/LocationBundle/Entity/City.php
namespace NNWelcome\LocationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="city")
 * @ORM\Entity(repositoryClass="NNWelcome\LocationBundle\Entity\Repository\CityRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\LocationBundle\Entity\CityTranslation")
 * @UniqueEntity(fields="title", message="Sorry, this city is already in use.", groups={"City"})
 */
class City {
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
     *   targetEntity="CityTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
     * @ORM\OneToMany(
     *   targetEntity="Area",
     *   mappedBy="city",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $areas;


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
     * @return City
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
     * @return City
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
     * @param \NNWelcome\LocationBundle\Entity\CityTranslation $translations
     * @return City
     */
    public function addTranslation(\NNWelcome\LocationBundle\Entity\CityTranslation $t)
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
     * @param \NNWelcome\LocationBundle\Entity\CityTranslation $translations
     */
    public function removeTranslation(\NNWelcome\LocationBundle\Entity\CityTranslation $translations)
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
     * Add areas
     *
     * @param \NNWelcome\LocationBundle\Entity\Area $areas
     * @return City
     */
    public function addArea(\NNWelcome\LocationBundle\Entity\Area $areas)
    {
        $this->areas[] = $areas;
    
        return $this;
    }

    /**
     * Remove areas
     *
     * @param \NNWelcome\LocationBundle\Entity\Area $areas
     */
    public function removeArea(\NNWelcome\LocationBundle\Entity\Area $areas)
    {
        $this->areas->removeElement($areas);
    }

    /**
     * Get areas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAreas()
    {
        return $this->areas;
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