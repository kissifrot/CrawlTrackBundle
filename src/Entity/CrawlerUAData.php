<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="crawler_ua_data", indexes={@ORM\Index(name="crawler_ua_idx", columns={"user_agent"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawlerUADataRepository")
 */
class CrawlerUAData
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(length=255, nullable=false)
     */
    private $userAgent;

    /**
     * Indicates whether the UA is exact or not
     * @ORM\Column(name="exact", type="boolean", options={"default":true}, nullable=false)
     */
    private $exact;

    /**
     * Indicates whether the UA is a Regexp or not
     * @ORM\Column(name="is_regexp", type="boolean", options={"default":false}, nullable=false)
     */
    private $regexp;

    /**
     * Internal use only (for reference crawler data updates)
     * @internal
     *
     * @ORM\Column(type="string", length=36, nullable=true)
     */
    private $uuid;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="userAgents")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id", nullable=false)
     */
    protected $crawler;

    public function __construct()
    {
        $this->exact = true;
    }

    public function __toString()
    {
        return $this->userAgent;
    }

    /**
     * @ORM\PrePersist
     */
    public function checkExact(): void
    {
        if ($this->regexp) {
            $this->exact = false;
        }
    }


    /**
     * Get id
     *
     * @return integer
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setCrawler(?Crawler $crawler): void
    {
        $this->crawler = $crawler;
    }

    public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }

    public function setRegexp(?string $regexp): void
    {
        $this->regexp = $regexp;
    }

    public function isRegexp(): bool
    {
        return $this->regexp;
    }

    public function isPartial(): bool
    {
        return !$this->exact;
    }

    public function setExact(bool $exact): void
    {
        $this->exact = $exact;
    }

    public function isExact(): bool
    {
        return $this->exact;
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
