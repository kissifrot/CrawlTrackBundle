<?php

namespace WebDL\CrawltrackBundle\Tracker;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\IpUtils as SfIpUtils;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Utils\IpUtils as WDLIpUtils;
use WebDL\CrawltrackBundle\Entity\CrawledPage;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class CrawlerTracker
{
    private $em;

    private $crawlerFound;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Store the robot visit, Try to match the most exact crawler first, then do more complex match with IP ranges and UAs)
     */
    public function track($ip, $userAgent, $uri)
    {
        $this->crawlerFound = $this->em->getRepository(Crawler::class)->findByExactIPOrUA($ip, $userAgent);
        if (!$this->crawlerFound) {
            // We didn't find exact IP or UA, let's check for more complex (slower), starting with IP
            if (!$this->checkByIP($ip)) {
                // No IP ranges stored or no corresponding IP found, let's check for User agent then (less accurate and reliable)
                if (empty($userAgent)) {
                    // No user agent provided, too bad
                    return false;
                }
                if (!$this->checkByUA($userAgent)) {
                    // Nothing found at all
                    return false;
                }
            }
        }

        if ($this->crawlerFound) {
            // Crawler found, we can check the page the crawler passed on
            $page = $this->em->getRepository(CrawledPage::class)->findByURI($uri);
            if (!$page) {
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
            if (!empty($userAgent)) {
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
    private function checkByIP($ip): bool
    {
        $crawlersWithIPRange = $this->em->getRepository(Crawler::class)->findByIPRanges();
        if (!$crawlersWithIPRange) {
            return false;
        }
        // Cycle through all the crawlers with IP ranges
        foreach ($crawlersWithIPRange as $crawlerWithIPRange) {
            // Cycle through all IP ranges
            foreach ($crawlerWithIPRange['ips'] as $ipData) {
                if ($this->isWithinIPRange($ip, $ipData['ipAddress'])) {
                    // Load the corresponding Crawler entity, as we need an entity object to add the visit later
                    $this->crawlerFound = $this->em->getRepository(Crawler::class)->find($crawlerWithIPRange['id']);
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
    private function checkByUA($userAgent): bool
    {
        if (empty($userAgent)) {
            // No user agent provided, too bad
            return false;
        }
        $crawlersWithUAs = $this->em->getRepository(Crawler::class)->findByComplexUAs();

        if (!$crawlersWithUAs) {
            return false;
        }
        // Cycle through all the crawlers with UAs
        foreach ($crawlersWithUAs as $crawlerWithUAs) {
            // Cycle through all UAs
            foreach ($crawlerWithUAs['UAs'] as $uaData) {
                if ($this->hasCorrespondingUA($userAgent, $uaData)) {
                    // Load the corresponding Crawler entity, as we need an entity object to add the visit later
                    $this->crawlerFound = $this->em->getRepository(Crawler::class)->find($crawlerWithUAs['id']);
                    return true;
                }
            }
        }
        return false;
    }

    private function isWithinIPRange($ip, $ipRange): bool
    {
        if (strpos($ipRange, '-') !== false || strpos($ipRange, '*') !== false) {
            try {
                return WDLIpUtils::iPInRange($ip, $ipRange);
            } catch (\Exception $e) {
                return false;
            }
        }
        if (strpos($ipRange, '/') !== false) {
            return SfIpUtils::checkIp($ip, $ipRange);
        }

        return false;
    }

    private function hasCorrespondingUA($userAgent, $userAgentData)
    {
        if ($userAgentData['isRegexp']) {
            return preg_match('#' . $userAgentData['userAgent'] . '#i', $userAgent);
        }

        // Partial correspondence, so let's just add wildcards to the original string
         return preg_match('#(.*)' . $userAgentData['userAgent'] . '(.*)#i', $userAgent);
    }
}
