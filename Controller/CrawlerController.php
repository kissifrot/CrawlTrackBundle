<?php

namespace WebDL\CrawltrackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class CrawlerController extends Controller
{
    public function viewAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $lastDays = 30;

        /** @var Crawler $crawler */
        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($request->get('crawler_id'));
        $visitsHits = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getForSpecificCrawlerLastDays($crawler, $lastDays);
        $visitsPages = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getPagesForSpecificCrawlerLastDays($crawler, $lastDays);

        $totalVisitsHits = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getForSpecificCrawlerTotal($crawler);
        $totalVisitsPages = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getPagesForSpecificCrawlerTotal($crawler);

        $totalPages = $em->getRepository('WebDLCrawltrackBundle:CrawledPage')->getTotalCount();

        $chartCategories = $chartHits = $chartPages  = array();
        foreach($visitsHits as $ind => $hit) {
            $chartCategories[] = $hit['dateVisit'];
            $chartHits[] = (int)$hit['nb'];
            $chartPages[] = (int)$visitsPages[$ind]['nb_pages'];
        }

        return $this->render('WebDLCrawltrackBundle:Crawler:view.html.twig', array(
                'crawler' => $crawler,
                'visitsHits' => $visitsHits,
                'visitsPages' => $visitsPages,
                'totalVisitsHits' => $totalVisitsHits,
                'totalVisitsPages' => $totalVisitsPages,
                'chartCategories' => json_encode($chartCategories),
                'chartHits' => json_encode($chartHits),
                'chartPages' => json_encode($chartPages),
                'totalPages' => $totalPages
            )
        );
    }
}
