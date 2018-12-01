<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="crawled_page", uniqueConstraints={@ORM\UniqueConstraint(name="crawler_uri_idx", columns={"uri"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Repository\CrawledPageRepository")
 */
class CrawledPage
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerVisit", mappedBy="page")
     */
    protected $visits;

    public function getId(): int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->visits = new ArrayCollection();
    }

    public function addVisit(CrawlerVisit $visit): void
    {
        $this->visits[] = $visit;
    }

    public function removeVisit(CrawlerVisit $visit): void
    {
        $this->visits->removeElement($visit);
    }

    public function getVisits(): iterable
    {
        return $this->visits;
    }

    public function setUri(string $uri): void
    {
        $this->uri = $uri;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
