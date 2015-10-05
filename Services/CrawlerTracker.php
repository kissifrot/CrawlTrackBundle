<?php
/**
 * Created by PhpStorm.
 * User: Kissifrot
 * Date: 05/04/2015
 * Time: 10:39
 */

namespace WebDL\CrawltrackBundle\Services;


use Doctrine\ORM\EntityManager;
use WebDL\CrawltrackBundle\Entity\CrawledPage;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Entity\CrawlerData;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class CrawlerTracker {
    /** @var  EntityManager */
    private $em;

    public function __construct($em) {
        $this->em = $em;
    }

    public function track($ip, $userAgent, $referer, $uri) {
        $crawler = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->findByIPOrUA($ip, $userAgent);
        if($crawler) {
            // Crawler found, we can check the page the crawler passed on
            $page = $this->em->getRepository('WebDLCrawltrackBundle:CrawledPage')->findByURI($uri);
            if(!$page) {
                $page = new CrawledPage();
                $page->setUri($uri);
                $this->em->persist($page);
                $this->em->flush();
                // Make sure we clear the result for this page
                $this->em->getConfiguration()->getResultCacheImpl()->delete('crawler_' . md5($uri));
            }
            // Now we can store the visit itself
            $pageVisit = new CrawlerVisit();
            $pageVisit->setCrawler($crawler);
            $pageVisit->setPage($page);
            $pageVisit->setFromIP($ip);
            $this->em->persist($pageVisit);
            $this->em->flush();
        }
    }
}