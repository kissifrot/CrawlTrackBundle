<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawledPage
 *
 * @ORM\Table(name="crawled_page", uniqueConstraints={@ORM\UniqueConstraint(name="crawler_uri_idx", columns={"uri"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawledPageRepository")
 */
class CrawledPage
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
     * @ORM\Column(name="uri", type="string", length=255)
     */
    private $uri;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerVisit", mappedBy="page")
     */
    protected $visits;


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
     * Constructor
     */
    public function __construct()
    {
        $this->visits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add visit
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerVisit $visit
     *
     * @return CrawledPage
     */
    public function addVisit(\WebDL\CrawltrackBundle\Entity\CrawlerVisit $visit)
    {
        $this->visits[] = $visit;

        return $this;
    }

    /**
     * Remove visit
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerVisit $visit
     */
    public function removeVisit(\WebDL\CrawltrackBundle\Entity\CrawlerVisit $visit)
    {
        $this->visits->removeElement($visit);
    }

    /**
     * Get visits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVisits()
    {
        return $this->visits;
    }

    /**
     * Set uri
     *
     * @param string $uri
     *
     * @return CrawledPage
     */
    public function setUri($uri)
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * Get uri
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }
}
