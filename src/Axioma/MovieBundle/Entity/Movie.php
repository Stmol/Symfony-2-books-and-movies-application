<?php

namespace Axioma\MovieBundle\Entity;

use Axioma\TagBundle\Entity\Tag;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Movie
 *
 * @ORM\Table(name="movies")
 * @ORM\Entity
 */
class Movie
{
    const
        QUALITY_DVDRIP = 'dvdrip',
        QUALITY_HDRIP  = 'hdrip',
        QUALITY_BDRIP  = 'bdrip',
        QUALITY_720    = '720p',
        QUALITY_1080   = '1080p',
        QUALITY_dvd5   = 'dvd5'
    ;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var array
     *
     * @ORM\Column(name="actors", type="simple_array", nullable=true)
     */
    private $actors;

    /**
     * @var string
     *
     * @ORM\Column(name="quality", type="string", length=255)
     */
    private $quality;

    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Axioma\TagBundle\Entity\Tag", inversedBy="movies", cascade={"persist"})
     * @ORM\JoinTable(name="movies_tags")
     */
    private $tags;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tags = new ArrayCollection();
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
     * @return Movie
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
     * @return Movie
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
     * Set actors
     *
     * @param array $actors
     * @return Movie
     */
    public function setActors($actors)
    {
        $this->actors = $actors;

        return $this;
    }

    /**
     * Get actors
     *
     * @return array 
     */
    public function getActors()
    {
        return $this->actors;
    }

    /**
     * Set quality
     *
     * @param string $quality
     *
     * @throws \InvalidArgumentException
     * @return Movie
     */
    public function setQuality($quality)
    {
        if (!in_array($quality, self::getQualities())) {
            throw new \InvalidArgumentException('Invalid quality value');
        }

        $this->quality = $quality;

        return $this;
    }

    /**
     * Get quality
     *
     * @return string 
     */
    public function getQuality()
    {
        return $this->quality;
    }

    /**
     * @return array
     */
    public static function getQualities()
    {
        return [
            self::QUALITY_HDRIP,
            self::QUALITY_DVDRIP,
            self::QUALITY_BDRIP,
            self::QUALITY_dvd5,
            self::QUALITY_720,
            self::QUALITY_1080,
        ];
    }

    /**
     * Add tags
     *
     * @param Tag $tags
     * @return Movie
     */
    public function addTag(Tag $tags)
    {
        $this->tags[] = $tags;

        return $this;
    }

    /**
     * Remove tags
     *
     * @param Tag $tags
     */
    public function removeTag(Tag $tags)
    {
        $this->tags->removeElement($tags);
    }

    /**
     * Get tags
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTags()
    {
        return $this->tags;
    }
}
