<?php

//src/NNWelcome/NticBundle/Entity/Hotel.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="hotel")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\HotelRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\NticBundle\Entity\HotelTranslation")
 * @UniqueEntity(fields="alias", message="Sorry, this alias is already in use.", groups={"Hotel"})
 */
class Hotel {

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
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank(message="Please enter alias.", groups={"Hotel"})
     * @Assert\Regex( 
     *       pattern="/^[a-z,A-Z,\_,\-,0-9]+$/",
     *       message="Alias can contain only letters, numbers and symbols '_' , '-'.", 
     *       groups={"Project"}
     * )
     */
    protected $alias;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $title;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    protected $operator;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $short_description;
    
    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;
    
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="decimal", nullable=true)
     */
    protected $latitude;
    
    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $is_active = true;

    /**
     * @ORM\OneToMany(
     *   targetEntity="HotelTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    private $translations;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="HotelImage", 
     *   mappedBy="hotel", 
     *   cascade={"persist", "remove"})
    */
    private $images;
    
    /**
    * @ORM\OneToMany(
     *   targetEntity="HotelFile", 
     *   mappedBy="hotel", 
     *   cascade={"persist", "remove"})
    */
    private $files;
    
    /**
     * @ORM\OneToOne(targetEntity="HotelMeta", 
     *   mappedBy="hotel", 
     *   cascade={"persist", "remove"})
    */
    protected $meta;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="ObjectType", 
     *   inversedBy="hotel")
     * @ORM\JoinColumn(
     *   name="object_type_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    private $object_type;
    
    /**
     * @ORM\ManyToOne(
     *   targetEntity="Price", 
     *   inversedBy="hotel")
     * @ORM\JoinColumn(
     *   name="price_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    private $price;
    
    protected $action;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
        $this->files = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Hotel
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
     * Set alias
     *
     * @param string $alias
     * @return Hotel
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    
        return $this;
    }

    /**
     * Get alias
     *
     * @return string 
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Hotel
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
     * Set short_description
     *
     * @param string $shortDescription
     * @return Hotel
     */
    public function setShortDescription($shortDescription)
    {
        $this->short_description = $shortDescription;
    
        return $this;
    }

    /**
     * Get short_description
     *
     * @return string 
     */
    public function getShortDescription()
    {
        return $this->short_description;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Hotel
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
     * Set operator
     *
     * @param string $operator
     * @return Hotel
     */
    public function setOperator($operator)
    {
        $this->operator = $operator;
    
        return $this;
    }

    /**
     * Get operator
     *
     * @return string 
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     * @return Hotel
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    
        return $this;
    }

    /**
     * Get longitude
     *
     * @return float 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     * @return Hotel
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    
        return $this;
    }

    /**
     * Get latitude
     *
     * @return float 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Hotel
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
     * @param \NNWelcome\NticBundle\Entity\HotelTranslation $translations
     * @return Hotel
     */
    public function addTranslation(\NNWelcome\NticBundle\Entity\HotelTranslation $translations)
    {
        $this->translations[] = $translations;
    
        return $this;
    }

    /**
     * Remove translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelTranslation $translations
     */
    public function removeTranslation(\NNWelcome\NticBundle\Entity\HotelTranslation $translations)
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
     * Add images
     *
     * @param \NNWelcome\NticBundle\Entity\HotelImage $images
     * @return Hotel
     */
    public function addImage(\NNWelcome\NticBundle\Entity\HotelImage $images)
    {
        $this->images[] = $images;
    
        return $this;
    }

    /**
     * Remove images
     *
     * @param \NNWelcome\NticBundle\Entity\HotelImage $images
     */
    public function removeImage(\NNWelcome\NticBundle\Entity\HotelImage $images)
    {
        $this->images->removeElement($images);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Add files
     *
     * @param \NNWelcome\NticBundle\Entity\HotelFile $files
     * @return Hotel
     */
    public function addFile(\NNWelcome\NticBundle\Entity\HotelFile $files)
    {
        $this->files[] = $files;
    
        return $this;
    }

    /**
     * Remove files
     *
     * @param \NNWelcome\NticBundle\Entity\HotelFile $files
     */
    public function removeFile(\NNWelcome\NticBundle\Entity\HotelFile $files)
    {
        $this->files->removeElement($files);
    }

    /**
     * Get files
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set meta
     *
     * @param \NNWelcome\NticBundle\Entity\HotelMeta $meta
     * @return Hotel
     */
    public function setMeta(\NNWelcome\NticBundle\Entity\HotelMeta $meta = null)
    {
        $this->meta = $meta;
    
        return $this;
    }

    /**
     * Get meta
     *
     * @return \NNWelcome\NticBundle\Entity\HotelMeta 
     */
    public function getMeta()
    {
        return $this->meta;
    }

    /**
     * Set object_type
     *
     * @param \NNWelcome\NticBundle\Entity\ObjectType $object
     * @return Hotel
     */
    public function setObjectType(\NNWelcome\NticBundle\Entity\ObjectType $object_type = null)
    {
        $this->object_type = $object_type;
    
        return $this;
    }

    /**
     * Get object_type
     *
     * @return \NNWelcome\NticBundle\Entity\ObjectType 
     */
    public function getObjectType()
    {
        return $this->object_type;
    }

    /**
     * Set price
     *
     * @param \NNWelcome\NticBundle\Entity\Price $price
     * @return Hotel
     */
    public function setPrice(\NNWelcome\NticBundle\Entity\Price $price = null)
    {
        $this->price = $price;
    
        return $this;
    }

    /**
     * Get price
     *
     * @return \NNWelcome\NticBundle\Entity\Price 
     */
    public function getPrice()
    {
        return $this->price;
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
    
    public function set($field, $value){
        $t = explode('_', $field);
        for($i = 0; $i <= count($t)-1; $i++){
            $t[$i] = ucfirst($t[$i]); 
        }
        call_user_func(array($this, 'set'.implode('', $t)), $value);
        return $this;
    }
}