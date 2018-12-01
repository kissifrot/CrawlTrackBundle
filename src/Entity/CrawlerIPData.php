<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CrawlerIPData
 *
 * @ORM\Table(name="crawler_ip_data",indexes={@ORM\Index(name="crawler_ip_idx", columns={"ip_address"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerIPDataRepository")
 * @ORM\HasLifecycleCallbacks
 */
class CrawlerIPData
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
     * @Assert\Ip(version="all")
     * @ORM\Column(name="ip_address", type="string", length=80, nullable=false)
     */
    private $ipAddress;

    /**
     * @var boolean
     *
     * @ORM\Column(name="single", type="boolean", options={"default":true}, nullable=false)
     */
    private $single;

    /**
     * @var boolean
     *
     * @ORM\Column(name="range", type="boolean", options={"default":false}, nullable=false)
     */
    private $range;

    /**
     * Internal use only (for reference crawler data updates)
     *
     * @var string
     * @internal
     *
     * @ORM\Column(name="ref_hash", type="string", length=13, nullable=true)
     */
    private $refHash;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="ips")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id", nullable=false)
     */
    protected $crawler;

    public function __construct() {
        $this->isSingle = true;
        $this->isRange = false;
    }

    public function __toString() {
        return $this->ipAddress;
    }

    /**
     * @ORM\PrePersist
     */
    public function checkSingle() {
        if(preg_match('#[/\*-]+#', $this->ipAddress)) {
            $this->single = false;
            $this->range = true;
        }
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
     * Set crawler
     *
     * @param \WebDL\CrawltrackBundle\Entity\Crawler $crawler
     *
     * @return CrawlerIPData
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
     * Set single
     *
     * @param boolean $single
     * @return CrawlerIPData
     */
    public function setSingle($single)
    {
        $this->single = $single;

        return $this;
    }

    /**
     * Get single
     *
     * @return boolean 
     */
    public function isSingle()
    {
        return $this->single;
    }

    /**
     * Set ipAddress
     *
     * @param string $ipAddress
     * @return CrawlerIPData
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress
     *
     * @return string 
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set range
     *
     * @param boolean $range
     * @return CrawlerIPData
     */
    public function setRange($range)
    {
        $this->range = $range;

        return $this;
    }

    /**
     * Get range
     *
     * @return boolean 
     */
    public function isRange()
    {
        return $this->range;
    }

    /**
     * Set refHash
     *
     * @param string $refHash
     * @return CrawlerIPData
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
}
