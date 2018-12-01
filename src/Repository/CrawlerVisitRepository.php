<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use WebDL\CrawltrackBundle\Entity\CrawledPage;
use WebDL\CrawltrackBundle\Entity\Crawler;
use WebDL\CrawltrackBundle\Entity\CrawlerVisit;

class CrawlerVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawlerVisit::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getForSpecificDateCrawlerCount(string $date, Crawler $crawler)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c)')
            ->where('SUBSTRING(c.visitDate, 1, 10) = :date')
            ->andWhere('c.crawler = :crawler')
            ->setParameter('date', $date)
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getForSpecificDateCrawlerPaginator(string $date, Crawler $crawler, CrawledPage $page, int $perPage): Paginator
    {
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

    public function getForSpecificCrawlerLastDays(Crawler $crawler, $diffDays = 30): array
    {
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


    public function getPagesForSpecificCrawlerLastDays(Crawler $crawler, $diffDays = 30): array
    {
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

    public function getForSpecificCrawlerTotal(Crawler $crawler)
    {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(cv)')
            ->where('cv.crawler = :crawler')
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getPagesForSpecificCrawlerTotal(Crawler $crawler)
    {
        return $this->createQueryBuilder('cv')
            ->select('COUNT(DISTINCT cv.page)')
            ->where('cv.crawler = :crawler')
            ->setParameter('crawler', $crawler)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
