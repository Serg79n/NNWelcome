<?php

//src/NNWelcome/NticBundle/Entity/HotelImage.php

namespace NNWelcome\NticBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="hotel_image")
 * @ORM\Entity(repositoryClass="NNWelcome\NticBundle\Entity\Repository\HotelImageRepository")
 * @Gedmo\TranslationEntity(class="NNWelcome\NticBundle\Entity\HotelImageTranslation")
 * @ORM\HasLifecycleCallbacks
 */
class HotelImage {

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
     * 
     * @Assert\File(maxSize="6000000")
     */
    protected $image;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $title;

    /**
     * @Gedmo\Translatable
     * @ORM\Column(type="text", nullable=true)
     */
    protected $description;

    /**
     * @ORM\OneToMany(
     *   targetEntity="HotelImageTranslation",
     *   mappedBy="object",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @ORM\ManyToOne(
     *   targetEntity="Hotel", 
     *   inversedBy="images")
     * @ORM\JoinColumn(
     *   name="hotel_id", 
     *   referencedColumnName="id", 
     *   onDelete="CASCADE")
     */
    protected $hotel;

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }
    
    public function getWebPath() {
        $path = $this->path;
        if($path === null || !file_exists($this->getUploadRootDir() . '/' . $this->path))
            $path = '/images/image_undefined.png';
        else
            $path = '/' . $this->getUploadDir() . '/' . $this->path;
        return $path;
    }

    protected function getUploadRootDir() {
        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir() {
        return 'uploads/images';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload() {
        if (null !== $this->image) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename . '.' . $this->image->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload() {
        if (null === $this->image) {
            return;
        }

        $this->image->move($this->getUploadRootDir(), $this->path);
        unset($this->image);
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload() {
        if ($image = $this->getAbsolutePath()) {
            if(file_exists($image))
                unlink($image);
        }
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->translations = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return HotelImage
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
     * Set image
     *
     * @param string $image
     * @return HotelImage
     */
    public function setImage($image) {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string 
     */
    public function getImage() {
        return $this->image;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return HotelImage
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return HotelImage
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Add translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelImageTranslation $translations
     * @return HotelImage
     */
    public function addTranslation(\NNWelcome\NticBundle\Entity\HotelImageTranslation $t) {
        if (!$this->translations->contains($t)) {
            $this->translations[] = $t;
            $t->setObject($this);
        }

        return $this;
    }

    /**
     * Remove translations
     *
     * @param \NNWelcome\NticBundle\Entity\HotelImageTranslation $translations
     */
    public function removeTranslation(\NNWelcome\NticBundle\Entity\HotelImageTranslation $translations) {
        $this->translations->removeElement($translations);
    }

    /**
     * Get translations
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTranslations() {
        return $this->translations;
    }

    /**
     * Set hotel
     *
     * @param \NNWelcome\NticBundle\Entity\Hotel $hotel
     * @return HotelImage
     */
    public function setHotel(\NNWelcome\NticBundle\Entity\Hotel $hotel = null) {
        $this->hotel = $hotel;

        return $this;
    }

    /**
     * Get hotel
     *
     * @return \NNWelcome\NticBundle\Entity\Hotel 
     */
    public function getHotel() {
        return $this->hotel;
    }


    /**
     * Set path
     *
     * @param string $path
     * @return HotelImage
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
}