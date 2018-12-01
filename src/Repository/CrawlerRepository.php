<?php

namespace WebDL\CrawltrackBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr;
use WebDL\CrawltrackBundle\Entity\Crawler;

class CrawlerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Crawler::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findById(int $id): ?Crawler
    {
        $q = $this->createQueryBuilder('c')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600);
        return $q->getOneOrNullResult();
    }

    public function findByExactIPOrUA(string $ip, ?string $ua = null): ?Crawler
    {
        $qb = $this->createQueryBuilder('c')
            ->leftJoin('c.ips', 'ipaddr', Expr\Join::WITH, 'ipaddr.single = :isSingleOrExact')
            ->leftJoin('c.userAgents', 'uas', Expr\Join::WITH, 'uas.exact = :isSingleOrExact')
            ->where('c.active = :isActive')
            ->andWhere('ipaddr.ipAddress = :ip');
        if (!empty($ua)) {
            $qb->orWhere('uas.userAgent = :ua')
            ->setParameter('ua', $ua);
        }
        $qb->setParameter('ip', $ip)
            ->setParameter('isActive', true)
            ->setParameter('isSingleOrExact', true);
        $q = $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600);
        try {
            return $q->getOneOrNullResult();
        } catch(\Exception $e) {
            return null;
        }
    }

    /**
     * Get a list of crawlers and their associated IP ranges
     */
    public function findByIPRanges(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.active = :isActive')
            ->innerJoin('c.ips', 'ipaddresses', Expr\Join::WITH, 'ipaddresses.single = :isSingle')
            ->setParameter('isActive', true)
            ->setParameter('isSingle', false)
            ->addSelect('ipaddresses');
        return $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600)
            ->getArrayResult();
    }

    /**
     * Get a list of crawlers and their associated "complex" user agents
     */
    public function findByComplexUAs(): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.active = :isActive')
            ->innerJoin('c.userAgents', 'uas', Expr\Join::WITH, 'uas.exact = :isExact')
            ->setParameter('isActive', true)
            ->setParameter('isExact', false)
            ->orderBy('uas.regexp', 'DESC') // results with regexp should appear first
            ->addSelect('uas');
        return $qb->getQuery()
            ->useQueryCache(true)
            ->useResultCache(true)
            ->setResultCacheLifetime(3600)
            ->getArrayResult();
    }

    public function getForSpecificDate(string $date): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.pageVisits', 'pv', Expr\Join::WITH, 'SUBSTRING(pv.visitDate, 1, 10) = :date')
            ->addSelect('pv')
            ->setParameter('date', $date)
            ->orderBy('c.name', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
