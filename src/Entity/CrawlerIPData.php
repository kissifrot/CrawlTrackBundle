<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="crawler_ip_data", indexes={@ORM\Index(name="crawler_ip_idx", columns={"ip_address"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawlerIPDataRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CrawlerIPData
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\Ip(version="all")
     * @ORM\Column(length=80, nullable=false)
     */
    private $ipAddress;

    /**
     * @ORM\Column(type="boolean", options={"default":true}, nullable=false)
     */
    private $single;

    /**
     * @ORM\Column(type="boolean", options={"default":false}, nullable=false)
     */
    private $range;

    /**
     * Internal use only (for reference crawler data updates)
     *
     * @internal
     *
     * @ORM\Column(length=36, nullable=true)
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="ips")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id", nullable=false)
     */
    protected $crawler;

    public function __construct()
    {
        $this->single = true;
        $this->range = false;
    }

    public function __toString() {
        return $this->ipAddress;
    }

    /**
     * @ORM\PrePersist
     */
    public function checkSingle(): void
    {
        if (preg_match('#[/\*-]+#', $this->ipAddress)) {
            $this->single = false;
            $this->range = true;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setCrawler(?Crawler $crawler): void
    {
        $this->crawler = $crawler;
    }

    public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }

    public function isSingle(): bool
    {
        return $this->single;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function isRange(): bool
    {
        return $this->range;
    }

    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }
}
