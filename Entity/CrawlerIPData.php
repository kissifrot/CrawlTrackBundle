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
     * @ORM\Column(name="is_single", type="boolean", options={"default":true}, nullable=false)
     */
    private $isSingle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_range", type="boolean", options={"default":false}, nullable=false)
     */
    private $isRange;

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
            $this->isSingle = false;
            $this->isRange = true;
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
     * Set isSingle
     *
     * @param boolean $isSingle
     * @return CrawlerIPData
     */
    public function setIsSingle($isSingle)
    {
        $this->isSingle = $isSingle;

        return $this;
    }

    /**
     * Get isSingle
     *
     * @return boolean 
     */
    public function getIsSingle()
    {
        return $this->isSingle;
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
     * Set isRange
     *
     * @param boolean $isRange
     * @return CrawlerIPData
     */
    public function setIsRange($isRange)
    {
        $this->isRange = $isRange;

        return $this;
    }

    /**
     * Get isRange
     *
     * @return boolean 
     */
    public function getIsRange()
    {
        return $this->isRange;
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
