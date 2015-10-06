<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Crawler
 *
 * @ORM\Table(name="crawler",uniqueConstraints={@ORM\UniqueConstraint(name="crawler_name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerRepository")
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=150)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="official_url", type="string", length=250, nullable=true)
     */
    private $officialURL;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerIPData", mappedBy="crawler")
     */
    protected $ips;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerUAData", mappedBy="crawler")
     */
    protected $UAs;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerVisit", mappedBy="crawler")
     */
    protected $pageVisits;


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
     * @return Crawler
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
     * Constructor
     */
    public function __construct()
    {
        $this->UAs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ips = new \Doctrine\Common\Collections\ArrayCollection();
        $this->pageVisits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pageVisit
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerVisit $pageVisit
     *
     * @return Crawler
     */
    public function addPageVisit(\WebDL\CrawltrackBundle\Entity\CrawlerVisit $pageVisit)
    {
        $this->pageVisits[] = $pageVisit;

        return $this;
    }

    /**
     * Remove pageVisit
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerVisit $pageVisit
     */
    public function removePageVisit(\WebDL\CrawltrackBundle\Entity\CrawlerVisit $pageVisit)
    {
        $this->pageVisits->removeElement($pageVisit);
    }

    /**
     * Get pageVisits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPageVisits()
    {
        return $this->pageVisits;
    }

    /**
     * Set officialURL
     *
     * @param string $officialURL
     *
     * @return Crawler
     */
    public function setOfficialURL($officialURL)
    {
        $this->officialURL = $officialURL;

        return $this;
    }

    /**
     * Get officialURL
     *
     * @return string
     */
    public function getOfficialURL()
    {
        return $this->officialURL;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Crawler
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
     * Add ips
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerIPData $ips
     * @return Crawler
     */
    public function addIp(\WebDL\CrawltrackBundle\Entity\CrawlerIPData $ips)
    {
        $this->ips[] = $ips;

        return $this;
    }

    /**
     * Remove ips
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerIPData $ips
     */
    public function removeIp(\WebDL\CrawltrackBundle\Entity\CrawlerIPData $ips)
    {
        $this->ips->removeElement($ips);
    }

    /**
     * Get ips
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * Add UAs
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerUAData $uAs
     * @return Crawler
     */
    public function addUA(\WebDL\CrawltrackBundle\Entity\CrawlerUAData $uAs)
    {
        $this->UAs[] = $uAs;

        return $this;
    }

    /**
     * Remove UAs
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerUAData $uAs
     */
    public function removeUA(\WebDL\CrawltrackBundle\Entity\CrawlerUAData $uAs)
    {
        $this->UAs->removeElement($uAs);
    }

    /**
     * Get UAs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUAs()
    {
        return $this->UAs;
    }
}
