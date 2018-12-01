<?php
/**
 * Event listener for Crawltrack
 */

namespace WebDL\CrawltrackBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class CrawltrackRequestListener
{
    private $crawlerTracker;

    public function __construct(CrawlerTracker $crawlerTracker) {
        $this->crawlerTracker = $crawlerTracker;
    }

    /**
     * Listener to KernelRequest event (a request is made)
     * @param GetResponseEvent $event Response event data
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            // Don't do anything if it's not the master request
            return;
        } else {
            $request = $event->getRequest();
            $uri = $request->getUri();
            // Don't do anything if we have either profiler or Ajax calls
            if (stripos($uri, '_profiler') !== false || $request->isXmlHttpRequest()) {
                return;
            }
            $ip = $request->getClientIp();
            $userAgent = $request->headers->get('User-Agent');
            // $referer = $request->headers->get('referer'); <-- Not used for now

            $this->crawlerTracker->track($ip, $userAgent, $uri);
        }
    }
}
