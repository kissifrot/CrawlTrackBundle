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
     * @ORM\Column(name="is_exact", type="boolean", options={"default":true}, nullable=false)
     */
    private $isExact;

    /**
     * Indicates whether the UA is a Regexp or not
     *
     * @var boolean
     *
     * @ORM\Column(name="is_regexp", type="boolean", options={"default":false}, nullable=false)
     */
    private $isRegexp;

    /**
     * Indicates whether the UA is partial or not
     *
     * @var boolean
     *
     * @ORM\Column(name="is_partial", type="boolean", options={"default":false}, nullable=false)
     */
    private $isPartial;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="UAs")
     * @ORM\JoinColumn(name="crawler_id", referencedColumnName="id")
     */
    protected $crawler;


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
     * Set isRegexp
     *
     * @param boolean $isRegexp
     * @return CrawlerUAData
     */
    public function setIsRegexp($isRegexp)
    {
        $this->isRegexp = $isRegexp;

        return $this;
    }

    /**
     * Get isRegexp
     *
     * @return boolean 
     */
    public function getIsRegexp()
    {
        return $this->isRegexp;
    }

    /**
     * Set isPartial
     *
     * @param boolean $isPartial
     * @return CrawlerUAData
     */
    public function setIsPartial($isPartial)
    {
        $this->isPartial = $isPartial;

        return $this;
    }

    /**
     * Get isPartial
     *
     * @return boolean 
     */
    public function getIsPartial()
    {
        return $this->isPartial;
    }

    /**
     * Set isExact
     *
     * @param boolean $isExact
     * @return CrawlerUAData
     */
    public function setIsExact($isExact)
    {
        $this->isExact = $isExact;

        return $this;
    }

    /**
     * Get isExact
     *
     * @return boolean 
     */
    public function getIsExact()
    {
        return $this->isExact;
    }
}
