<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlerVisit
 *
 * @ORM\Table(name="crawler_visit")
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerVisitRepository")
 */
class CrawlerVisit
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
     * @var \DateTime
     *
     * @ORM\Column(name="visit_date", type="datetime")
     */
    private $visitDate;


    /**
     * @var string
     *
     * @ORM\Column(name="from_ip", type="string", length=40)
     */
    private $fromIP;


    /**
     * @var string
     *
     * @ORM\Column(name="from_ua", type="string", length=255, nullable=true)
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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->visitDate = new \DateTime();
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
     * Set visitDate
     *
     * @param \DateTime $visitDate
     *
     * @return CrawlerVisit
     */
    public function setVisitDate($visitDate)
    {
        $this->visitDate = $visitDate;

        return $this;
    }

    /**
     * Get visitDate
     *
     * @return \DateTime
     */
    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * Set crawler
     *
     * @param \WebDL\CrawltrackBundle\Entity\Crawler $crawler
     *
     * @return CrawlerVisit
     */
    public function setCrawler(\WebDL\CrawltrackBundle\Entity\Crawler $crawler = null)
    {
        $this->crawler = $crawler;

        return $this;
    }

    /**
     * Get crawler
     *
     * @return \WebDL\CrawltrackBundle\Entity\Crawler
     */
    public function getCrawler()
    {
        return $this->crawler;
    }

    /**
     * Set page
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawledPage $page
     *
     * @return CrawlerVisit
     */
    public function setPage(\WebDL\CrawltrackBundle\Entity\CrawledPage $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return \WebDL\CrawltrackBundle\Entity\CrawledPage
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set fromIP
     *
     * @param string $fromIP
     *
     * @return CrawlerVisit
     */
    public function setFromIP($fromIP)
    {
        $this->fromIP = $fromIP;

        return $this;
    }

    /**
     * Get fromIP
     *
     * @return string
     */
    public function getFromIP()
    {
        return $this->fromIP;
    }

    /**
     * Set fromUA
     *
     * @param string $fromUA
     * @return CrawlerVisit
     */
    public function setFromUA($fromUA)
    {
        $this->fromUA = $fromUA;

        return $this;
    }

    /**
     * Get fromUA
     *
     * @return string 
     */
    public function getFromUP()
    {
        return $this->fromUA;
    }
}
