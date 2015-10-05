<?php

namespace WebDL\CrawltrackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use WebDL\CrawltrackBundle\Entity\Crawler;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $crawlsForToday = $em->getRepository('WebDLCrawltrackBundle:Crawler')->getForSpecificDate($today);
        $crawlsForYesterday = $em->getRepository('WebDLCrawltrackBundle:Crawler')->getForSpecificDate($yesterday);

        return $this->render('WebDLCrawltrackBundle:Default:index.html.twig', array(
                'today' => $today,
                'yesterday' => $yesterday,
                'crawlsForToday' => $crawlsForToday,
                'crawlsForYesterday' => $crawlsForYesterday
            )
        );
    }
}
