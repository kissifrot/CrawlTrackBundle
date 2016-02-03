<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CrawltrackConfig entity, stores config in key/value pairs
 *
 * @ORM\Table(name="crawltrack_cfg")
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerRepository")
 * @UniqueEntity("name")
 *
 */
class CrawltrackConfig
{
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=150, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="value", type="string", length=150, nullable=false)
     */
    private $value;

    public function __toString() {
        return $this->name . ':' . $this->value;
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
     * Set name
     *
     * @param string $name
     *
     * @return CrawltrackConfig
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return CrawltrackConfig
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}
