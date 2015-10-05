<?php

namespace WebDL\CrawltrackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class VisitsController extends Controller
{
    /**
     * @var int Number of visits to display per page
     */
    private $perPage = 100;

    public function showByDateCrawlerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $date = $request->get('crawl_date');

        $page = $request->get('page');

        /** @var Crawler $crawler */
        $crawler = $em->getRepository('WebDLCrawltrackBundle:Crawler')->find($request->get('crawler_id'));
        $visitsCount = (int)$em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getForSpecificDateCrawlerCount($date, $crawler);
        $pagesCount = ceil($visitsCount / $this->perPage);
        if($pagesCount > 30) {
            $this->perPage *= 2;
            $pagesCount = ceil($visitsCount / $this->perPage);
        }

        /** @var CrawlerVisit $visits */
        $visits = $em->getRepository('WebDLCrawltrackBundle:CrawlerVisit')->getForSpecificDateCrawlerPaginator($date, $crawler, $page, $this->perPage);

        return $this->render('WebDLCrawltrackBundle:Visits:showbydatecrawler.html.twig', array(
                'crawler' => $crawler,
                'crawlDate' => $date,
                'visits' => $visits,
                'visitsCount' => $visitsCount,
                'pagesCount' => $pagesCount,
                'page' => $page
            )
        );
    }
}
