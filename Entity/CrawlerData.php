<?php

namespace WebDL\CrawltrackBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CrawlerData
 *
 * @ORM\Table(name="crawler_data",indexes={@ORM\Index(name="crawler_ip_idx", columns={"ip"})})
 * @ORM\Entity(repositoryClass="WebDL\CrawltrackBundle\Entity\CrawlerDataRepository")
 */
class CrawlerData
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
     * @ORM\Column(name="ip", type="string", length=15, nullable=true)
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="userAgent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * @ORM\ManyToOne(targetEntity="Crawler", inversedBy="ipUAs")
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
     * Set ip
     *
     * @param string $ip
     *
     * @return CrawlerData
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return CrawlerData
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
     * @return CrawlerData
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
}
