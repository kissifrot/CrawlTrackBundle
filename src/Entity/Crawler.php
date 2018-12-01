<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Table(name="crawler",uniqueConstraints={@ORM\UniqueConstraint(name="crawler_name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawlerRepository")
 * @UniqueEntity("name")
 */
class Crawler
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
     * @Assert\NotBlank()
     * @ORM\Column(length=150, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean", options={"default":false}, nullable=false)
     */
    private $active;

    /**
     * Is the crawler harmful? Used for scans and such
     *
     * @ORM\Column(type="boolean", options={"default":false}, nullable=false)
     */
    private $harmful;

    /**
     * Internal use only (for reference crawlers updates)
     * @internal
     *
     * @ORM\Column(length=36, nullable=true)
     */
    private $uuid;

    /**
     * @ORM\Column(length=250, nullable=true)
     */
    private $officialUrl;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerIPData", mappedBy="crawler", cascade={"persist", "remove"})
     */
    private $ips;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerUAData", mappedBy="crawler", cascade={"persist", "remove"})
     */
    private $userAgents;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerVisit", mappedBy="crawler", cascade={"persist", "remove"})
     */
    private $pageVisits;

    public function __construct()
    {
        $this->userAgents = new ArrayCollection();
        $this->ips = new ArrayCollection();
        $this->pageVisits = new ArrayCollection();
        $this->active = true;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
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

    public function addPageVisit(CrawlerVisit $pageVisit): void
    {
        $this->pageVisits->add($pageVisit);
    }

    public function removePageVisit(CrawlerVisit $pageVisit): void
    {
        $this->pageVisits->removeElement($pageVisit);
    }

    public function getPageVisits(): ArrayCollection
    {
        return $this->pageVisits;
    }

    public function setOfficialUrl(?string $officialUrl): void
    {
        $this->officialUrl = $officialUrl;
    }

    public function getOfficialUrl(): ?string
    {
        return $this->officialUrl;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function addIp(CrawlerIPData $ips): void
    {
        $ips->setCrawler($this);
        $this->ips->add($ips);
    }

    public function removeIp(CrawlerIPData $ips): void
    {
        $this->ips->removeElement($ips);
    }

    public function getIps(): ArrayCollection
    {
        return $this->ips;
    }

    public function addUserAgent(CrawlerUAData $userAgent): void
    {
        $userAgent->setCrawler($this);
        $this->userAgents->add($userAgent);
    }

    public function removeUserAgent(CrawlerUAData $userAgents): void
    {
        $this->userAgents->removeElement($userAgents);
    }

    public function getUserAgents(): ArrayCollection
    {
        return $this->userAgents;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function setHarmful(bool $harmful): void
    {
        $this->harmful = $harmful;
    }

    public function isHarmful(): bool
    {
        return $this->harmful;
    }
}
