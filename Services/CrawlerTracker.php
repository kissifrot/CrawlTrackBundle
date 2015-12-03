<?php
/**
 * CrawlerTracker service
 */

namespace WebDL\CrawltrackBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\IpUtils as SfIpUtils;
use WebDL\CrawltrackBundle\Utils\IpUtils as WDLIpUtils;
use WebDL\CrawltrackBundle\Entity\CrawledPage;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class CrawlerTracker {
    /** @var  EntityManager */
    private $em;

    private $crawlerFound;

    public function __construct($em) {
        $this->em = $em;
    }

    /**
     * Store the robot wisit, if crawler data match (first exact match, then more complex match with IP ranges and UAs)
     *
     * @param string $ip IP(v4/v6) of the crawler
     * @param string $userAgent User Agent of the crawler
     * @param string $referer Referer URL (not used atm)
     * @param string $uri Page visited URI
     * @return bool
     */
    public function track($ip, $userAgent, $referer, $uri) {
        $this->crawlerFound = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->findByExactIPOrUA($ip, $userAgent);
        if(!$this->crawlerFound) {
            // We didn't find exact IP or UA, let's check for more complex (slower), starting with IP
            if(!$this->checkByIP($ip)) {
                // No IP ranges stored or no correpondign IP found, let's check for User agent then (less accurate and reliable)
                if(empty($userAgent)) {
                    // No user agent provided, too bad
                    return false;
                }
                if(!$this->checkByUA($userAgent)) {
                    // Nothing found at all
                    return false;
                }
            }
        }

        if($this->crawlerFound) {
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
            $pageVisit->setCrawler($this->crawlerFound);
            $pageVisit->setPage($page);
            $pageVisit->setFromIP($ip);
            if(!empty($userAgent)) {
                // $pageVisit->setFromUA($userAgent); <-- disabled for now
            }
            $this->em->persist($pageVisit);
            $this->em->flush();

            return true;
        }

        return false;
    }

    /**
     * Check if the provided IP can be found within the crawler IP ranges
     * @param string $ip IP(v4/v6) address of the crawler
     * @return bool
     */
    private function checkByIP($ip) {
        $crawlersWithIPRange = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->findByIPRanges();
        if(!$crawlersWithIPRange) {
            return false;
        }
        // Cycle through all the crawlers with IP ranges
        foreach($crawlersWithIPRange as $crawlerWithIPRange) {
            // Cycle through all IP ranges
            foreach($crawlerWithIPRange['ips'] as $ipData) {
                if($this->isWithinIPRange($ip, $ipData['ipAddress'])) {
                    // Load the corresponding Crawler entity, as we need an entity object to add the visit later
                    $this->crawlerFound = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->find($crawlerWithIPRange['id']);
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if the provided user agent can be found within the UAs regex or partial values
     * @param string $userAgent User Agent of the crawler
     * @return bool
     */
    private function checkByUA($userAgent) {
        if(empty($userAgent)) {
            // No user agent provided, too bad
            return false;
        }
        $crawlersWithUAs = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->findByComplexUAs();

        if(!$crawlersWithUAs) {
            return false;
        }
        // Cycle through all the crawlers with UAs
        foreach($crawlersWithUAs as $crawlerWithUAs) {
            // Cycle through all UAs
            foreach($crawlerWithUAs['UAs'] as $uaData) {
                if($this->hasCorrespondingUA($userAgent, $uaData)) {
                    // Load the corresponding Crawler entity, as we need an entity object to add the visit later
                    $this->crawlerFound = $this->em->getRepository('WebDLCrawltrackBundle:Crawler')->find($crawlerWithUAs['id']);
                    return true;
                }
            }
        }
        return false;
    }

    private function isWithinIPRange($ip, $ipRange) {
        if (strpos($ipRange, '-') !== false || strpos($ipRange, '*') !== false) {
            return WDLIpUtils::IPInRange($ip, $ipRange);
        }
        if (strpos($ipRange, '/') !== false) {
            return SfIpUtils::checkIp($ip, $ipRange);
        }

        return false;
    }

    private function hasCorrespondingUA($userAgent, $userAgentData) {
        if($userAgentData['isRegexp']) {
            return preg_match('#' . $userAgentData['userAgent'] . '#i', $userAgent);
        } else {
            // Partial correspondance, so let's just add wildcards to the orignal string
            return preg_match('#(.*)' . $userAgentData['userAgent'] . '(.*)#i', $userAgent);
        }
    }
}
