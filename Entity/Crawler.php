<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Crawler
 *
 * @ORM\Table(name="crawler",uniqueConstraints={@ORM\UniqueConstraint(name="crawler_name_idx", columns={"name"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerRepository")
 * @UniqueEntity("name")
 *
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=150, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean", options={"default":false}, nullable=false)
     */
    private $active;

    /**
     * Is the crawler harmful? Used for scans and such
     *
     * @var boolean
     *
     * @ORM\Column(name="harmful", type="boolean", options={"default":false}, nullable=false)
     */
    private $harmful;

    /**
     * Internal use only (for reference crawlers updates)
     *
     * @var string
     * @internal
     *
     * @ORM\Column(name="ref_hash", type="string", length=13, nullable=true)
     */
    private $refHash;

    /**
     * @var string
     *
     * @ORM\Column(name="official_url", type="string", length=250, nullable=true)
     */
    private $officialURL;

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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userAgents = new ArrayCollection();
        $this->ips = new ArrayCollection();
        $this->pageVisits = new ArrayCollection();
        $this->active = true;
    }

    public function __toString() {
        return $this->name;
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
        $ips->setCrawler($this);

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
     * Add userAgents
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerUAData $userAgents
     * @return Crawler
     */
    public function addUserAgent(\WebDL\CrawltrackBundle\Entity\CrawlerUAData $userAgents)
    {
        $userAgents->setCrawler($this);

        $this->userAgents[] = $userAgents;

        return $this;
    }

    /**
     * Remove userAgents
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerUAData $uAs
     */
    public function removeUserAgent(\WebDL\CrawltrackBundle\Entity\CrawlerUAData $userAgents)
    {
        $this->userAgents->removeElement($userAgents);
    }

    /**
     * Get userAgents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserAgents()
    {
        return $this->userAgents;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Crawler
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean 
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Set refHash
     *
     * @param string $refHash
     * @return Crawler
     */
    public function setRefHash($refHash)
    {
        $this->refHash = $refHash;

        return $this;
    }

    /**
     * Get refHash
     *
     * @return string 
     */
    public function getRefHash()
    {
        return $this->refHash;
    }

    /**
     * Set harmful
     *
     * @param boolean $harmful
     * @return Crawler
     */
    public function setHarmful($harmful)
    {
        $this->harmful = $harmful;

        return $this;
    }

    /**
     * Get harmful
     *
     * @return boolean 
     */
    public function isHarmful()
    {
        return $this->harmful;
    }
}
