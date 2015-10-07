<?php

namespace WebDL\CrawltrackBundle\Entity;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CrawlerVisitRepository
 */
class CrawlerVisitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getForSpecificDateCrawlerCount($date, $crawler) {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('SUBSTRING(c.visitDate, 1, 10) = :date')
            ->andWhere('c.crawler = :crawler')
            ->setParameter('date', $date)
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getForSpecificDateCrawlerPaginator($date, $crawler, $page, $perPage) {
        $q = $this->createQueryBuilder('c')
            ->leftJoin('c.page', 'p')
            ->where('SUBSTRING(c.visitDate, 1, 10) = :date')
            ->andWhere('c.crawler = :crawler')
            ->setParameter('date', $date)
            ->setParameter('crawler', $crawler)
            ->orderBy('c.visitDate', 'DESC')
            ->addSelect('p')
            ->getQuery()
            ->setFirstResult(($page-1) * $perPage)
            ->setMaxResults($perPage);

        return new Paginator($q);
    }

    public function getForSpecificCrawlerLastDays($crawler, $diffDays = 30) {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(cv) as nb, SUBSTRING(cv.visitDate, 1, 10) as dateVisit')
            ->where('cv.crawler = :crawler')
            ->andWhere('cv.visitDate >= :date')
            ->groupBy('dateVisit')
            ->orderBy('dateVisit', 'ASC')
            ->setParameter('crawler', $crawler)
            ->setParameter('date', (new \DateTime())->sub(new \DateInterval('P' . $diffDays . 'D')))
            ->getQuery()
            ->getArrayResult();
    }


    public function getPagesForSpecificCrawlerLastDays($crawler, $diffDays = 30) {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(DISTINCT cv.page) as nb_pages, SUBSTRING(cv.visitDate, 1, 10) as dateVisit')
            ->where('cv.crawler = :crawler')
            ->andWhere('cv.visitDate >= :date')
            ->groupBy('dateVisit')
            ->orderBy('dateVisit', 'ASC')
            ->setParameter('crawler', $crawler)
            ->setParameter('date', (new \DateTime())->sub(new \DateInterval('P' . $diffDays . 'D')))
            ->getQuery()
            ->getArrayResult();
    }

    public function getForSpecificCrawlerTotal($crawler) {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(cv)')
            ->where('cv.crawler = :crawler')
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPagesForSpecificCrawlerTotal($crawler) {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(DISTINCT cv.page)')
            ->where('cv.crawler = :crawler')
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
