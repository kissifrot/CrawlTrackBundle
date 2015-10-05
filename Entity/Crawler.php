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
     * @ORM\Column(name="official_url", type="string", length=250, nullable=true)
     */
    private $officialURL;

    /**
     * @ORM\OneToMany(targetEntity="CrawlerData", mappedBy="crawler")
     */
    protected $ipUAs;

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
        $this->ipUAs = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ipUA
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerData $ipUA
     *
     * @return Crawler
     */
    public function addIpUA(\WebDL\CrawltrackBundle\Entity\CrawlerData $ipUA)
    {
        $this->ipUAs[] = $ipUA;

        return $this;
    }

    /**
     * Remove ipUA
     *
     * @param \WebDL\CrawltrackBundle\Entity\CrawlerData $ipUA
     */
    public function removeIpUA(\WebDL\CrawltrackBundle\Entity\CrawlerData $ipUA)
    {
        $this->ipUAs->removeElement($ipUA);
    }

    /**
     * Get ipUAs
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIpUAs()
    {
        return $this->ipUAs;
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
}
