<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlerUAData
 *
 * @ORM\Table(name="crawler_ua_data",indexes={@ORM\Index(name="crawler_ua_idx", columns={"user_agent"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerDataRepository")
 */
class CrawlerUAData
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
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=false)
     */
    private $userAgent;

    /**
     * Indicates whether the UA is exact or not
     *
     * @var boolean
     *
     * @ORM\Column(name="exact", type="boolean", options={"default":true}, nullable=false)
     */
    private $exact;

    /**
     * Indicates whether the UA is a Regexp or not
     *
     * @var boolean
     *
     * @ORM\Column(name="is_regexp", type="boolean", options={"default":false}, nullable=false)
     */
    private $regexp;

    /**
     * Indicates whether the UA is partial or not
     *
     * @var boolean
     *
     * @ORM\Column(name="partial", type="boolean", options={"default":false}, nullable=false)
     */
    private $partial;

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
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="userAgents")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id", nullable=false)
     */
    protected $crawler;

    public function __construct() {
        $this->exact = true;
        $this->partial = false;
    }

    public function __toString() {
        return $this->userAgent;
    }

    /**
     * @ORM\PrePersist
     */
    public function checkExact() {
        if($this->regexp || $this->partial) {
            $this->exact = false;
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
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return CrawlerUAData
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set crawler
     *
     * @param \WebDL\CrawltrackBundle\Entity\Crawler $crawler
     *
     * @return CrawlerUAData
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
     * Set regexp
     *
     * @param boolean $regexp
     * @return CrawlerUAData
     */
    public function setRegexp($regexp)
    {
        $this->regexp = $regexp;

        return $this;
    }

    /**
     * Get regexp
     *
     * @return boolean 
     */
    public function isRegexp()
    {
        return $this->regexp;
    }

    /**
     * Set partial
     *
     * @param boolean $partial
     * @return CrawlerUAData
     */
    public function setPartial($partial)
    {
        $this->partial = $partial;

        return $this;
    }

    /**
     * Get partial
     *
     * @return boolean 
     */
    public function isPartial()
    {
        return $this->partial;
    }

    /**
     * Set exact
     *
     * @param boolean $exact
     * @return CrawlerUAData
     */
    public function setIsExact($exact)
    {
        $this->exact = $exact;

        return $this;
    }

    /**
     * Get isExact
     *
     * @return boolean 
     */
    public function isExact()
    {
        return $this->exact;
    }

    /**
     * Set refHash
     *
     * @param string $refHash
     * @return CrawlerUAData
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
