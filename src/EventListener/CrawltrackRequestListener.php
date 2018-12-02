<?php

namespace WebDL\CrawltrackBundle\EventListener;

use Symfony\Component\HttpFoundation\IpUtils;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use WebDL\CrawltrackBundle\Tracker\CrawlerTracker;

class CrawltrackRequestListener
{
    private $crawlerTracker;
    private $blacklistedIps;

    public function __construct(CrawlerTracker $crawlerTracker, array $blacklistedIps)
    {
        $this->crawlerTracker = $crawlerTracker;
        $this->blacklistedIps = $blacklistedIps;
    }

    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            // Don't do anything if it's not the master request
            return;
        }
        $request = $event->getRequest();
        $uri = $request->getUri();
        // Don't do anything if we have either profiler or Ajax calls
        if (false !== stripos($uri, '_profiler') || $request->isXmlHttpRequest()) {
            return;
        }
        $ip = $request->getClientIp();
        foreach ($this->blacklistedIps as $blacklistedIp) {
            if (IpUtils::checkIp($ip, $blacklistedIp)) {
                return;
            }
        }

        $userAgent = $request->headers->get('User-Agent');
        // $referer = $request->headers->get('referer'); <-- Not used for now

        $this->crawlerTracker->track($ip, $userAgent, $uri);
    }
}
