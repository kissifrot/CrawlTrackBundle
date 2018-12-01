<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * CrawltrackConfig entity, Stores config in key/value pairs
 *
 * @ORM\Table(name="crawltrack_cfg")
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawlerRepository")
 * @UniqueEntity("name")
 */
class CrawltrackConfig
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(length=150, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(length=150, nullable=false)
     */
    private $value;

    public function __toString()
    {
        return $this->name . ':' . $this->value;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
