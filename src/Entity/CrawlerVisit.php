<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlerVisit
 *
 * @ORM\Table(name="crawler_visit")
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawlerVisitRepository")
 */
class CrawlerVisit
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $visitDate;

    /**
     * @ORM\Column(name="from_ip", length=40)
     */
    private $fromIP;

    /**
     * @ORM\Column(name="from_ua", length=255, nullable=true)
     */
    private $fromUA;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="pageVisits")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id")
     */
    protected $crawler;

    /**
     * @ORM\ManyToOne(targetEntity="CrawledPage", inversedBy="visits")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id")
     */
    protected $page;

    public function __construct()
    {
        $this->visitDate = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVisitDate(): \DateTimeImmutable
    {
        return $this->visitDate;
    }

    public function setCrawler(?Crawler $crawler): void
    {
        $this->crawler = $crawler;
    }

    public function getCrawler(): ?Crawler
    {
        return $this->crawler;
    }

    public function setPage(?CrawledPage $page): void
    {
        $this->page = $page;
    }

    public function getPage(): ?CrawledPage
    {
        return $this->page;
    }

    public function setFromIP(string $fromIP): void
    {
        $this->fromIP = $fromIP;
    }

    public function getFromIP(): string
    {
        return $this->fromIP;
    }

    public function setFromUA($fromUA)
    {
        $this->fromUA = $fromUA;

        return $this;
    }

    public function getFromUA(): ?string
    {
        return $this->fromUA;
    }
}
